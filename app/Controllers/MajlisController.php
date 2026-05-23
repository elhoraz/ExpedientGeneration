<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MajlisTopicModel;
use App\Models\MajlisVoteModel;
use App\Models\UserModel;
use App\Services\PusherService;

class MajlisController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $currentUser = $userModel->find($userId);

        return view('majlis_syura', [
            'user_id'   => $userId,
            'user_role' => $currentUser['role'] ?? 'member',
        ]);
    }

    public function pusherAuth()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        $socketId = $this->request->getPost('socket_id');
        $channelName = $this->request->getPost('channel_name');

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $userInfo = [
            'name' => $user['nama_panggilan'] ?? $user['nama_lengkap'],
            'avatar' => $user['foto_profil'] ? base_url('uploads/profiles/' . $user['foto_profil']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama_panggilan'] ?? 'User') . '&background=random',
            'role' => $user['role'] ?? 'member'
        ];

        $pusher = new PusherService();
        $auth = $pusher->presenceAuth($channelName, $socketId, (string)$userId, $userInfo);

        return $this->response->setContentType('application/json')->setBody($auth);
    }

    public function getState()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        $topics = $db->query("
            SELECT 
                t.*, 
                u.nama_panggilan,
                COALESCE(SUM(CASE WHEN v.vote_choice = 'Setuju' THEN 1 ELSE 0 END), 0) AS votes_setuju,
                COALESCE(SUM(CASE WHEN v.vote_choice = 'Tidak Setuju' THEN 1 ELSE 0 END), 0) AS votes_tidak_setuju,
                MAX(CASE WHEN v.user_id = ? THEN 1 ELSE 0 END) AS has_voted
            FROM majlis_topics t
            JOIN users u ON u.id = t.created_by
            LEFT JOIN majlis_votes v ON v.topic_id = t.id
            GROUP BY t.id
            ORDER BY t.created_at DESC
        ", [$userId])->getResultArray();

        foreach ($topics as &$topic) {
            $topic['has_voted'] = (bool) $topic['has_voted'];
        }

        $activeSpeaker = cache('majlis_active_speaker');
        $requests = cache('majlis_speaker_requests') ?: [];

        return $this->response->setJSON([
            'status' => 'success',
            'topics' => $topics,
            'active_speaker' => $activeSpeaker,
            'requests' => array_values($requests),
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function raiseHand()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $requests = cache('majlis_speaker_requests') ?: [];
        $requests[$userId] = [
            'user_id' => $userId,
            'name' => $user['nama_panggilan'] ?? $user['nama_lengkap'],
            'avatar' => $user['foto_profil'] ? base_url('uploads/profiles/' . $user['foto_profil']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama_panggilan'] ?? 'User') . '&background=random',
            'role' => $user['role'] ?? 'member'
        ];

        cache()->save('majlis_speaker_requests', $requests, 86400);

        $pusher = new PusherService();
        $pusher->trigger('presence-majlis', 'hand-raised', ['requests' => array_values($requests)]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Permintaan berbicara dikirim.', 'csrf_hash' => csrf_hash()]);
    }

    public function approveSpeaker()
    {
        $adminId = session()->get('user_id');
        $userModel = new UserModel();
        $admin = $userModel->find($adminId);
        if (($admin['role'] ?? '') !== 'admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $approvedUserId = $this->request->getPost('user_id');
        $requests = cache('majlis_speaker_requests') ?: [];

        if (isset($requests[$approvedUserId])) {
            $speaker = $requests[$approvedUserId];
            cache()->save('majlis_active_speaker', $speaker, 86400);
            unset($requests[$approvedUserId]);
            cache()->save('majlis_speaker_requests', $requests, 86400);

            $pusher = new PusherService();
            $pusher->trigger('presence-majlis', 'speaker-changed', [
                'speaker' => $speaker,
                'requests' => array_values($requests)
            ]);

            return $this->response->setJSON(['status' => 'success', 'message' => 'Pembicara disetujui.', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Permintaan tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
    }

    public function stopSpeaker()
    {
        $adminId = session()->get('user_id');
        $userModel = new UserModel();
        $admin = $userModel->find($adminId);
        if (($admin['role'] ?? '') !== 'admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        cache()->delete('majlis_active_speaker');
        
        $pusher = new PusherService();
        $pusher->trigger('presence-majlis', 'speaker-changed', ['speaker' => null, 'requests' => array_values(cache('majlis_speaker_requests') ?: [])]);

        return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        $rules = [
            'title'       => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Validasi gagal.', 'csrf_hash' => csrf_hash()]);
        }

        $topicModel = new MajlisTopicModel();
        $topicModel->insert([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'created_by'  => $userId,
            'status'      => 'Open',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        $pusher = new PusherService();
        $pusher->trigger('presence-majlis', 'majlis-update', []);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Mosi musyawarah berhasil diajukan.', 'csrf_hash' => csrf_hash()]);
    }

    public function vote($topicId)
    {
        $userId = session()->get('user_id');
        $choice = $this->request->getPost('choice'); 
        
        $allowedChoices = ['Setuju', 'Tidak Setuju'];
        if (!in_array($choice, $allowedChoices)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pilihan tidak valid.', 'csrf_hash' => csrf_hash()]);
        }

        $topicModel = new MajlisTopicModel();
        $topic = $topicModel->find($topicId);

        if (!$topic || $topic['status'] !== 'Open') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sesi pemungutan suara telah ditutup.', 'csrf_hash' => csrf_hash()]);
        }

        $voteModel = new MajlisVoteModel();
        $hasVoted = $voteModel->where('topic_id', $topicId)->where('user_id', $userId)->first();

        if ($hasVoted) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah memberikan suara.', 'csrf_hash' => csrf_hash()]);
        }

        $voteModel->insert([
            'topic_id'    => $topicId,
            'user_id'     => $userId,
            'vote_choice' => $choice,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        $pusher = new PusherService();
        $pusher->trigger('presence-majlis', 'majlis-update', []);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Suara Anda telah direkam.', 'csrf_hash' => csrf_hash()]);
    }

    public function close($topicId)
    {
        $userId = session()->get('user_id');
        $topicModel = new MajlisTopicModel();
        $topic = $topicModel->find($topicId);

        if (!$topic) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Mosi tidak ditemukan.', 'csrf_hash' => csrf_hash()]);
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $isCreator = (int)$topic['created_by'] === (int)$userId;
        $isAdmin = $user && ($user['role'] ?? '') === 'admin';

        if (!$isCreator && !$isAdmin) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Hanya pembuat mosi atau admin yang dapat menutup voting.', 'csrf_hash' => csrf_hash()]);
        }

        $topicModel->update($topicId, ['status' => 'Closed']);

        $pusher = new PusherService();
        $pusher->trigger('presence-majlis', 'majlis-update', []);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Sesi voting telah ditutup.', 'csrf_hash' => csrf_hash()]);
    }
}
