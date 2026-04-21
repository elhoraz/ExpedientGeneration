<?php

namespace App\Controllers;

use App\Models\UserModel;

class DirektoriController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        
        // Mengambil semua data alumni, diurutkan berdasarkan nama secara alfabet
        $data['alumni'] = $userModel->orderBy('nama_lengkap', 'ASC')->findAll();

        // Tampilkan halaman direktori dan bawa datanya
        return view('direktori', $data);
    }
}