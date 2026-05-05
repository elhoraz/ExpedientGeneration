<?php

namespace App\Controllers;

use App\Models\UserModel;

class EnigmaController extends BaseController
{
    public function index()
    {
        // 1. Cek Login Dasar
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses Ditolak.');
        }

        return view('enigma_vault');
    }
}
