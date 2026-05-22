<?php

namespace App\Controllers;

use App\Services\NexusService;

class NexusController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/login');

        $data = [
            'title' => 'The Nexus - Prediksi Eksekutif',
            'user_id' => $userId
        ];

        return view('nexus', $data);
    }

    public function calculateMatches()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $nexusService = service('nexusService');
        $matches = $nexusService->findTopMatches($userId, 5);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $matches
        ]);
    }
}
