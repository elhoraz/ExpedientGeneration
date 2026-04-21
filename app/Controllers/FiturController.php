<?php

namespace App\Controllers;

use App\Models\UserModel;

class FiturController extends BaseController
{
    public function index()
    {
        $session = session();

        // 1. AUTENTIKASI LAPIS 1
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses Ditolak. Silakan login terlebih dahulu.');
        }

        // 2. Ambil data user
        $userModel = new UserModel();
        $userId = $session->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Sesi tidak valid.');
        }

        // 3. Ambil matriks wajah
        $faceData = empty($user['face_data']) ? 'null' : $user['face_data'];

        // 4. Kirim data wajah DAN data user ke Halaman View
        $data = [
            'face_data_db' => $faceData,
            'user'         => $user // <-- PERBAIKAN: Dibawa agar bisa dipanggil di View
        ];

        return view('fitur', $data);
    }

    // Fungsi menerima sinyal Face ID dari Javascript
    public function unlockVaultSession()
    {
        $session = session();
        
        // Pastikan user sudah login dasar
        if (!$session->get('logged_in')) {
            // PERBAIKAN: Gunakan HTTP Status 401 Unauthorized
            return $this->response->setStatusCode(401)
                                  ->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        // PERBAIKAN: Set status unlock DAN catat waktu persis brankas dibuka
        $session->set([
            'vault_unlocked' => true,
            'vault_opened_at' => time() // Bisa digunakan nanti untuk Auto-Lock
        ]);
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Vault Unlocked']);
    }
}