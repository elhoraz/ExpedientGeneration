<?php

namespace App\Controllers;

use App\Models\UserModel;

class SovereignController extends BaseController
{
    public function index()
    {
        $session = session();



        // 2. Ambil Data Entitas
        $userModel = new UserModel();
        $user = $userModel->find($session->get('user_id'));

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Entitas tidak ditemukan.');
        }

        // 3. PAKSA LOAD URL (Tanpa file_exists yang sering rewel)
        $fotoUrl = '';
        if (!empty($user['foto_profil'])) {
            // Langsung tembak ke URL folder profiles
            $fotoUrl = base_url('uploads/profiles/' . $user['foto_profil']);
        }

        $data = [
            'user' => $user,
            'foto_profil' => $fotoUrl
        ];

        return view('sovereign', $data);
    }
}