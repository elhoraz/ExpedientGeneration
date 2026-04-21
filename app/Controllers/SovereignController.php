<?php

namespace App\Controllers;

use App\Models\UserModel;

class SovereignController extends BaseController
{
    public function index()
    {
        $session = session();

        // 1. Cek Login Dasar
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses Ditolak.');
        }

        // 2. THE GUARD DOG: Cek Biometrik
        if (!$session->get('vault_unlocked')) {
            return redirect()->to('/fitur')->with('error', 'Akses Ilegal: Otorisasi Biometrik Diperlukan!');
        }

        // 3. AUTO-LOCK MECHANISM
        $openedAt = $session->get('vault_opened_at');
        if ($openedAt && (time() - $openedAt > 900)) { 
            $session->remove(['vault_unlocked', 'vault_opened_at']);
            return redirect()->to('/fitur')->with('error', 'Sesi Keamanan Habis. Brankas terkunci otomatis.');
        }

        // 4. Ambil Data Entitas
        $userModel = new UserModel();
        $user = $userModel->find($session->get('user_id'));

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Entitas tidak ditemukan.');
        }

        // 5. Cek Foto Profil (Menggunakan field 'foto_profil' sesuai UserModsel)
        // Jika ada fotonya di folder public/uploads/profil/, kita kirim URL-nya.
        // Jika kosong, kita kirim string kosong agar 3D merender siluet default.
        $fotoUrl = '';
        if (!empty($user['foto_profil']) && file_exists(FCPATH . 'uploads/profil/' . $user['foto_profil'])) {
            $fotoUrl = base_url('uploads/profiles/' . $user['foto_profil']);
        }

        $data = [
            'user' => $user,
            'foto_profil' => $fotoUrl
        ];

        return view('sovereign', $data);
    }
}