<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        // Pastikan hanya agen yang sudah login yang bisa masuk
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
        }

        // Ambil data user dari database berdasarkan session ID
        $userModel = new UserModel();
        $data['user'] = $userModel->find(session()->get('user_id'));

        return view('profil', $data);
    }
}