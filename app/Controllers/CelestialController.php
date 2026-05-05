<?php

namespace App\Controllers;

class CelestialController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses Ditolak.');
        }

        return view('celestial_codex');
    }
}
