<?php

namespace App\Controllers;

class GaleriController extends BaseController
{
    public function index()
    {
        // Simulasi Data Foto Galeri dari Database (Bisa Anda ganti nanti)
        // Pastikan Anda menaruh beberapa foto acak di public/images/ untuk tes ini
        $data['memories'] = [
            ['file' => '/images/logo-utuh.png', 'title' => 'The Genesis', 'date' => '12.04.2023', 'location' => 'Sektor Arrisalah'],
            ['file' => '/images/globe.png', 'title' => 'Global Vision', 'date' => '05.08.2024', 'location' => 'Titik Nol'],
            ['file' => '/images/mahkota-emas.png', 'title' => 'Crowning Moment', 'date' => '21.12.2025', 'location' => 'Aula Utama'],
            ['file' => '/images/kristal-puncak.png', 'title' => 'Peak of the Mountain', 'date' => '01.01.2026', 'location' => 'Puncak Visi'],
            ['file' => '/images/perisai-bendera.png', 'title' => 'Shield of Honor', 'date' => '17.08.2025', 'location' => 'Basecamp 42'],
            ['file' => '/images/tanduk-perak.png', 'title' => 'Steel Defense', 'date' => '09.09.2024', 'location' => 'Garis Depan'],
            ['file' => '/images/cincin-emas.png', 'title' => 'The Unbreakable Vow', 'date' => '14.02.2026', 'location' => 'Ruang Sidang'],
            ['file' => '/images/segi-delapan-gelap.png', 'title' => 'Dark Foundations', 'date' => '03.03.2023', 'location' => 'Bawah Tanah'],
        ];

        return view('galeri', $data);
    }
}