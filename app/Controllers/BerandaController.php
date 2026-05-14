<?php

namespace App\Controllers;

use App\Services\BerandaService;
use App\Services\GamificationService;

class BerandaController extends BaseController
{
    public function index()
    {
        $berandaService = new BerandaService();
        $data = $berandaService->getDashboardData();

        return view('beranda', $data);
    }

    // Ide 9: Penampung Submit Buku Tamu
    public function simpan_pesan()
    {
        $rules = [
            'nama'  => 'required|min_length[2]|max_length[100]',
            'pesan' => 'required|min_length[5]|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/beranda')->withInput()->with('error', 'Gagal mengirim pesan. Pastikan nama dan pesan telah diisi dengan benar.');
        }

        // Tangkap input dari form
        $nama = $this->request->getPost('nama');
        $pesan = $this->request->getPost('pesan');

        // Simpan ke tabel buku_tamu menggunakan service
        $berandaService = new BerandaService();
        $berandaService->saveGuestBookMessage($nama, $pesan);

        if (session()->has('user_id')) {
            $gamificationService = new GamificationService();
            $gamificationService->addPrestise(session()->get('user_id'), 'GUESTBOOK_ENTRY', 10);
        }

        return redirect()->to('/beranda')->with('pesan', 'Transmisi pesan Anda telah diukir di pameran ini.');
    }
}