<?php

namespace App\Controllers;

use App\Models\UserModel;

class FiturController extends BaseController
{
    public function index()
    {
        $session = session();



        // 2. Ambil data user
        $userModel = new UserModel();
        $userId = $session->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Sesi tidak valid.');
        }

        // 4. Kirim data user ke Halaman View
        $data = [
            'user' => $user
        ];

        return view('fitur', $data);
    }

}