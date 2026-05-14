<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WasiatModel;
use App\Services\WasiatService;
use App\Services\GamificationService;

class WasiatController extends BaseController
{
    public function index()
    {
        $wasiatModel = new WasiatModel();
        
        $data['wasiats'] = $wasiatModel->withUser()
            ->orderBy('wasiat_messages.created_at', 'DESC')
            ->paginate(10);
        $data['pager'] = $wasiatModel->pager;

        return view('wasiat_vault', $data);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        $rules = [
            'message'    => 'required|min_length[10]',
            'passphrase' => 'required|min_length[4]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/wasiat')->withInput()->with('error', 'Gagal menyimpan wasiat. Pastikan pesan dan sandi (passphrase) telah diisi.');
        }

        $message = $this->request->getPost('message');
        $passphrase = $this->request->getPost('passphrase');

        $wasiatService = new WasiatService();
        $wasiatService->encryptAndStore($userId, $message, $passphrase);

        $gamificationService = new GamificationService();
        $gamificationService->addPrestise($userId, 'WASIAT_STORE', 10);

        return redirect()->to('/wasiat')->with('success', 'Amanah Anda telah disegel dengan aman di dalam Ruang Wasiat.');
    }

    public function unlock($id)
    {
        $passphrase = $this->request->getPost('passphrase');
        $wasiatService = new WasiatService();
        
        try {
            $decrypted = $wasiatService->unlock($id, $passphrase);
            if ($decrypted !== null) {
                $gamificationService = new GamificationService();
                $gamificationService->addPrestise(session()->get('user_id'), 'WASIAT_UNLOCK', 20);

                return redirect()->to('/wasiat')
                    ->with('success', 'Segel wasiat berhasil dibuka.')
                    ->with('unlocked_wasiat', $decrypted);
            }
        } catch (\Exception $e) {
            return redirect()->to('/wasiat')->with('error', $e->getMessage());
        }

        return redirect()->to('/wasiat')->with('error', 'Kunci akses tidak sesuai. Mohon periksa kembali sandi Anda.');
    }
}
