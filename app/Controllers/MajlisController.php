<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MajlisTopicModel;
use App\Models\MajlisVoteModel;

class MajlisController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // ================= OPTIMIZED: 1 query instead of N+1 =================
        // Mengambil semua topics + vote counts + user vote status dalam SATU query
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

        // Cast has_voted ke boolean
        foreach ($topics as &$topic) {
            $topic['has_voted'] = (bool) $topic['has_voted'];
        }

        // Get current user role
        $userModel = new \App\Models\UserModel();
        $currentUser = $userModel->find($userId);

        return view('majlis_syura', [
            'topics'    => $topics,
            'user_id'   => $userId,
            'user_role' => $currentUser['role'] ?? 'member',
        ]);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        $rules = [
            'title'       => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/majlis')->with('error', 'Gagal membuat mosi. Cek kembali isian Anda.');
        }

        $topicModel = new MajlisTopicModel();
        $topicModel->insert([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'created_by'  => $userId,
            'status'      => 'Open',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        $pusher = new \App\Services\PusherService();
        $pusher->broadcastNotification(
            'Mosi Baru di Majlis',
            $this->request->getPost('title'),
            '/majlis'
        );

        return redirect()->to('/majlis')->with('success', 'Mosi musyawarah berhasil diajukan ke forum.');
    }

    public function vote($topicId)
    {
        $userId = session()->get('user_id');
        $choice = $this->request->getPost('choice'); // Setuju atau Tidak Setuju

        $topicModel = new MajlisTopicModel();
        $topic = $topicModel->find($topicId);

        if (!$topic || $topic['status'] !== 'Open') {
            return redirect()->to('/majlis')->with('error', 'Sesi pemungutan suara telah ditutup atau tidak ada.');
        }

        $voteModel = new MajlisVoteModel();
        $hasVoted = $voteModel->where('topic_id', $topicId)->where('user_id', $userId)->first();

        if ($hasVoted) {
            return redirect()->to('/majlis')->with('error', 'Anda sudah memberikan suara.');
        }

        $voteModel->insert([
            'topic_id'    => $topicId,
            'user_id'     => $userId,
            'vote_choice' => $choice,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/majlis')->with('success', 'Suara Anda telah direkam di Majlis.');
    }

    /**
     * Tutup sesi voting (hanya pembuat mosi atau admin).
     */
    public function close($topicId)
    {
        $userId = session()->get('user_id');
        $topicModel = new MajlisTopicModel();
        $topic = $topicModel->find($topicId);

        if (!$topic) {
            return redirect()->to('/majlis')->with('error', 'Mosi tidak ditemukan.');
        }

        // Cek otorisasi: pembuat mosi atau admin
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $isCreator = (int)$topic['created_by'] === (int)$userId;
        $isAdmin = $user && ($user['role'] ?? '') === 'admin';

        if (!$isCreator && !$isAdmin) {
            return redirect()->to('/majlis')->with('error', 'Hanya pembuat mosi atau admin yang dapat menutup voting.');
        }

        $topicModel->update($topicId, ['status' => 'Closed']);

        return redirect()->to('/majlis')->with('success', 'Sesi voting telah ditutup.');
    }
}
