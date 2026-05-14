<?php

namespace App\Services;

class GaleriService
{
    /**
     * Mengambil data memori galeri.
     * Saat ini menggunakan data statis, ke depannya bisa diarahkan ke Model Galeri.
     * 
     * @return array
     */
    public function getGaleriMemories()
    {
        return [
            ['file' => '/images/logo-utuh.png', 'title' => 'The Genesis', 'date' => '12.04.2023', 'location' => 'Sektor Arrisalah'],
            ['file' => '/images/globe.png', 'title' => 'Global Vision', 'date' => '05.08.2024', 'location' => 'Titik Nol'],
            ['file' => '/images/mahkota-emas.png', 'title' => 'Crowning Moment', 'date' => '21.12.2025', 'location' => 'Aula Utama'],
            ['file' => '/images/kristal-puncak.png', 'title' => 'Peak of the Mountain', 'date' => '01.01.2026', 'location' => 'Puncak Visi'],
            ['file' => '/images/perisai-bendera.png', 'title' => 'Shield of Honor', 'date' => '17.08.2025', 'location' => 'Basecamp 42'],
            ['file' => '/images/tanduk-perak.png', 'title' => 'Steel Defense', 'date' => '09.09.2024', 'location' => 'Garis Depan'],
            ['file' => '/images/cincin-emas.png', 'title' => 'The Unbreakable Vow', 'date' => '14.02.2026', 'location' => 'Ruang Sidang'],
            ['file' => '/images/segi-delapan-gelap.png', 'title' => 'Dark Foundations', 'date' => '03.03.2023', 'location' => 'Bawah Tanah'],
        ];
    }
}
