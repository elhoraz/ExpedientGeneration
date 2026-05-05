<?php

namespace App\Controllers;

class GenesisController extends BaseController
{
    public function index()
    {
        // Pengecekan Login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses Ditolak.');
        }

        return view('genesis_core');
    }
}
