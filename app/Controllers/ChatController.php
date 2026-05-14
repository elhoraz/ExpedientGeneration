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

        $message = $this->request->getPost('message');
        $receiverId = $this->request->getPost('receiver_id');
        $receiverId = empty($receiverId) ? null : (int)$receiverId;

        if (empty(trim($message))) return $this->response->setJSON(['status' => 'error', 'message' => 'Pesan kosong', 'csrf_hash' => csrf_hash()]);

        // Simpan pesan ke DB
        $chatData = [
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $chatId = $this->chatModel->insert($chatData);

        // Ambil data user untuk dikirim ke Pusher
        $user = $this->userModel->find($userId);

        // =========================================================
        // TRIGGER PUSHER
        // =========================================================
        $pusherService = new PusherService();
        $pusherService->sendChatMessage([
            'id' => $chatId,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'sender_name' => $user['nama_lengkap'],
            'sender_avatar' => $user['foto_profil'] ?? 'default.webp',
            'message' => esc($message),
            'created_at' => $chatData['created_at']
        ]);

        return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
    }
    public function getUnreadCount()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error', 'count' => 0]);

        $count = $this->chatModel->where('receiver_id', $userId)
                                ->where('is_read', 0)
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
