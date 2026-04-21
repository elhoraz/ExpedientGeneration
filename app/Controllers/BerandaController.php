<?php

namespace App\Controllers;

use App\Models\UserModel;

class BerandaController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        
        // 1. Ambil Total Alumni Asli dari Database
        $totalAlumni = $userModel->countAllResults();

        // 2. Data Dummy (Sementara) sambil menunggu Anda membuat tabel aslinya di PHPMyAdmin
        $data = [
            'total_alumni' => $totalAlumni > 0 ? $totalAlumni : 450, // Fallback jika tabel masih kosong
            'total_provinsi' => 12, 
            'tahun_kebangkitan' => 2026,
            
            // Ide 3: Lorong Kenangan
            'galeri' => [
                ['file' => '/images/globe.png', 'caption' => 'Memori Pertama'],
                ['file' => '/images/cincin-emas.png', 'caption' => 'Ikatan Persaudaraan'],
                ['file' => '/images/mahkota-emas.png', 'caption' => 'Amanah Kepemimpinan'],
                ['file' => '/images/kristal-puncak.png', 'caption' => 'Visi Puncak'],
            ],

            // Ide 5: Manuskrip / Berita
            'berita' => [
                ['tanggal' => '12 Mar 2026', 'judul' => 'Proyek Lagu Angkatan Resmi Dimulai', 'slug' => '#'],
                ['tanggal' => '05 Feb 2026', 'judul' => 'Pemilihan Jajaran Kurator Syndicate', 'slug' => '#'],
            ],

            // Ide 6: Para Kurator (Nanti bisa diganti dengan model SyndicateModel)
            'kurator' => [
                ['nama' => 'Sang Komandan', 'jabatan' => 'Ketua Syndicate', 'foto' => '/images/ornamen-bawah-emas.png'],
                ['nama' => 'Juru Kunci', 'jabatan' => 'Sekretaris Eksekutif', 'foto' => '/images/segi-delapan-perak.png'],
            ],

            // Ide 8: Garis Waktu
            'timeline' => [
                ['tahun' => '2020', 'deskripsi' => 'Pijakan pertama di tanah Arrisalah. Langkah awal sebuah epik.'],
                ['tahun' => '2026', 'deskripsi' => 'Ekspedisi dimulai. Kami menyebar ke seluruh penjuru sebagai pelopor.'],
            ]
        ];

        return view('beranda', $data);
    }

    // Ide 9: Penampung Submit Buku Tamu
    public function simpan_pesan()
    {
        // Tangkap input dari form
        $nama = $this->request->getPost('nama');
        $pesan = $this->request->getPost('pesan');

        // Nanti masukkan query insert ke tabel buku_tamu di sini
        // $db->table('buku_tamu')->insert(['nama' => $nama, 'pesan' => $pesan]);

        return redirect()->to('/beranda')->with('success', 'Transmisi pesan Anda telah diukir di pameran ini.');
    }
}