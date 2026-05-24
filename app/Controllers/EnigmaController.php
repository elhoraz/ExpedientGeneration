<?php

namespace App\Controllers;

use App\Models\EnigmaModel;

class EnigmaController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $enigmaModel = new EnigmaModel();

        // Cari atau buat progres enigma untuk agen ini
        $progress = $enigmaModel->where('user_id', $userId)->first();

        if (!$progress) {
            $enigmaModel->insert([
                'user_id' => $userId,
                'puzzle_seed' => 'COMBINATION_LOCK',
                'current_level' => 3, // Tidak ada level bertahap lagi
                'is_completed' => false
            ]);
            $progress = $enigmaModel->where('user_id', $userId)->first();
        }

        $data['progress'] = $progress;
        
        return view('enigma_vault', $data);
    }

    public function verify()
    {
        $userId = session()->get('user_id');
        $enigmaModel = new EnigmaModel();
        $progress = $enigmaModel->where('user_id', $userId)->first();

        if (!$progress || $progress['is_completed']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Simpul telah terpecahkan.']);
        }

        // Ambil kombinasi dari JSON payload
        $json = $this->request->getJSON();
        if (!isset($json->combination) || !is_array($json->combination) || count($json->combination) !== 3) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sinyal tidak valid.']);
        }

        $combo = $json->combination; // [outer, middle, inner]
        
        // Target: VIII (7), V (4), I (0)
        if ($combo[0] === 7 && $combo[1] === 4 && $combo[2] === 0) {
            // Jawaban Benar
            $enigmaModel->update($progress['id'], [
                'is_completed' => true,
                'completed_at' => date('Y-m-d H:i:s')
            ]);
            return $this->response->setJSON(['success' => true]);
        }

        // Jawaban Salah
        return $this->response->setJSON(['success' => false]);
    }

    public function reset()
    {
        $userId = session()->get('user_id');
        $enigmaModel = new \App\Models\EnigmaModel();
        $progress = $enigmaModel->where('user_id', $userId)->first();
        
        if ($progress) {
            $enigmaModel->update($progress['id'], [
                'is_completed' => false
            ]);
        }
        
        return redirect()->to('/enigma');
    }
}
