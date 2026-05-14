<?php

namespace App\Controllers;

use App\Models\UserModel;

class DirektoriController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $isLoggedIn = session()->get('logged_in');

        if ($isLoggedIn) {
            // Anggota: data lengkap
            $data['alumni'] = $userModel->orderBy('nama_lengkap', 'ASC')->paginate(24);
        } else {
            // Publik: hanya nama & foto (tanpa data kontak sensitif)
            $data['alumni'] = $userModel
                ->select('id, nama_lengkap, nama_panggilan, foto_profil')
                ->orderBy('nama_lengkap', 'ASC')
                ->paginate(24);
        }

        $data['pager'] = $userModel->pager;
        $data['isLoggedIn'] = $isLoggedIn;

        return view('direktori', $data);
    }
}