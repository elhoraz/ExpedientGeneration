<?php

namespace App\Controllers;

class DivineController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses Ditolak.');
        }

        return view('divine_verse');
    }
}
