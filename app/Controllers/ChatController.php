<?php

namespace App\Controllers;

use App\Models\ChatModel;
use App\Models\UserModel;
use App\Services\PusherService;

class ChatController extends BaseController
{
    protected $chatModel;
    protected $userModel;

    public function __construct()
    {
        $this->chatModel = new ChatModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('user_id')) return redirect()->to('/login');

        // Inbox view
        $inbox = $this->chatModel->getInbox(session()->get('user_id'));

        $data = [
            'title' => 'Kotak Masuk',
            'inbox' => $inbox
        ];

        return view('chat_inbox', $data);
    }

    public function lounge()
    {
        if (!session()->get('user_id')) return redirect()->to('/login');

        $messages = $this->chatModel->withUser()
            ->where('receiver_id', null)
            ->orderBy('chat_messages.created_at', 'ASC')
            ->findAll(50);

        $data = [
            'title' => 'The Lounge - Eksekutif Chat',
            'messages' => $messages,
            'user_id' => session()->get('user_id'),
            'receiver' => null
        ];

        return view('chat', $data);
    }

    public function personal($receiverId)
    {
        if (!session()->get('user_id')) return redirect()->to('/login');
        
        $myId = session()->get('user_id');
        if ($myId == $receiverId) return redirect()->to('/chat');

        $receiver = $this->userModel->find($receiverId);
        if (!$receiver) return redirect()->to('/chat')->with('error', 'Kolega tidak ditemukan.');

        $messages = $this->chatModel->withUser()
            ->groupStart()
                ->where('sender_id', $myId)
                ->where('receiver_id', $receiverId)
            ->groupEnd()
            ->orGroupStart()
                ->where('sender_id', $receiverId)
                ->where('receiver_id', $myId)
            ->groupEnd()
            ->where('chat_messages.is_deleted', 0)
            ->orderBy('chat_messages.created_at', 'ASC')
            ->findAll(50);

        // Mark as read
        $this->chatModel->where('sender_id', $receiverId)
                        ->where('receiver_id', $myId)
                        ->where('is_read', 0)
                        ->set(['is_read' => 1])
                        ->update();

        $data = [
            'title' => 'Obrolan Eksklusif: ' . ($receiver['nama_panggilan'] ?: $receiver['nama_lengkap']),
            'messages' => $messages,
            'user_id' => $myId,
            'receiver' => $receiver
        ];

        return view('chat', $data);
    }

    public function send()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);

        $message = $this->request->getPost('message') ?? '';
        $receiverId = $this->request->getPost('receiver_id');
        $receiverId = empty($receiverId) ? null : (int)$receiverId;

        // Cek apakah ada file gambar
        $imageFile = $this->request->getFile('chat_image');
        $imagePath = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Validasi tipe dan ukuran (max 3MB)
            if (!in_array($imageFile->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Format gambar tidak didukung.', 'csrf_hash' => csrf_hash()]);
            }
            if ($imageFile->getSizeByUnit('mb') > 3) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Ukuran gambar maks 3MB.', 'csrf_hash' => csrf_hash()]);
            }

            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'uploads/chat/', $newName);
            $imagePath = $newName;
        }

        // Validasi: pesan atau gambar harus ada
        if (empty(trim($message)) && empty($imagePath)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan kosong', 'csrf_hash' => csrf_hash()]);
        }

        // Validasi panjang pesan (maks 1000 karakter)
        if (mb_strlen($message) > 1000) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan terlalu panjang (maks 1000 karakter).', 'csrf_hash' => csrf_hash()]);
        }

        // Simpan pesan ke DB
        $chatData = [
            'sender_id'   => $userId,
            'receiver_id' => $receiverId,
            'message'     => $message,
            'image_path'  => $imagePath,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $chatId = $this->chatModel->insert($chatData);

        // Ambil data user untuk dikirim ke Pusher
        $user = $this->userModel->find($userId);

        // Trigger Pusher
        $pusherService = service('pusherService');
        $pusherPayload = [
            'id'            => $chatId,
            'sender_id'     => $userId,
            'receiver_id'   => $receiverId,
            'sender_name'   => $user['nama_lengkap'],
            'sender_avatar' => $user['foto_profil'] ?? 'default.webp',
            'message'       => esc($message),
            'image_path'    => $imagePath,
            'created_at'    => $chatData['created_at']
        ];
        
        $pusherResult = $pusherService->sendChatMessage($pusherPayload);
        log_message('info', '[ChatController::send] Pusher trigger result: ' . ($pusherResult ? 'SUCCESS' : 'FAILED') . ' | chatId=' . $chatId);

        return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
    }

    /**
     * Hapus pesan sendiri (soft delete).
     */
    public function deleteMessage($msgId)
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error', 'csrf_hash' => csrf_hash()]);

        $msg = $this->chatModel->find($msgId);

        if (!$msg || (int)$msg['sender_id'] !== (int)$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak diizinkan.', 'csrf_hash' => csrf_hash()]);
        }

        $this->chatModel->update($msgId, ['is_deleted' => 1]);

        return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
    }

    /**
     * Load More: ambil pesan lebih lama dari ID tertentu.
     */
    public function loadMore()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error']);

        $beforeId = (int)$this->request->getGet('before_id');
        $receiverId = $this->request->getGet('receiver_id');
        $receiverId = empty($receiverId) || $receiverId === 'null' ? null : (int)$receiverId;

        $builder = $this->chatModel->withUser();

        if ($receiverId === null) {
            // Lounge
            $builder->where('receiver_id', null);
        } else {
            $builder->groupStart()
                ->groupStart()
                    ->where('sender_id', $userId)
                    ->where('receiver_id', $receiverId)
                ->groupEnd()
                ->orGroupStart()
                    ->where('sender_id', $receiverId)
                    ->where('receiver_id', $userId)
                ->groupEnd()
            ->groupEnd();
        }

        if ($beforeId > 0) {
            $builder->where('chat_messages.id <', $beforeId);
        }

        $messages = $builder
            ->orderBy('chat_messages.created_at', 'DESC')
            ->findAll(20);

        // Reverse supaya urutan kronologis
        $messages = array_reverse($messages);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $messages,
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function getUnreadCount()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error', 'count' => 0]);

        $count = $this->chatModel->where('receiver_id', $userId)
                                ->where('is_read', 0)
                                ->where('is_deleted', 0)
                                ->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'count' => $count
        ]);
    }

    public function markAsRead($senderId)
    {
        $myId = session()->get('user_id');
        if (!$myId) return $this->response->setJSON(['status' => 'error']);

        $this->chatModel->where('sender_id', $senderId)
                        ->where('receiver_id', $myId)
                        ->where('is_read', 0)
                        ->set(['is_read' => 1])
                        ->update();

        return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
    }
}
