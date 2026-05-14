<?php

namespace App\Controllers;

class LandingController extends BaseController
{
    public function index()
    {
        // Jika sudah login, langsung ke beranda
        if (session()->get('logged_in')) {
            return redirect()->to('/beranda');
        }

        // Ambil statistik ringkas untuk landing page
        $db = \Config\Database::connect();
        $totalAlumni = $db->table('users')
            ->where('email_verified_at IS NOT NULL')
            ->countAllResults();

        return view('landing', [
            'total_alumni' => $totalAlumni,
        ]);
    }
}
