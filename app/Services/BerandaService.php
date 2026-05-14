<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\BukuTamuModel;
use App\Models\AnnouncementModel;

/**
 * BerandaService
 * 
 * Mengelola agregasi data dan statistik untuk halaman Beranda (Dashboard VVIP).
 */
class BerandaService
{
    protected UserModel $userModel;
    protected BukuTamuModel $bukuTamuModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bukuTamuModel = new \App\Models\BukuTamuModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Mengambil data terpadu untuk halaman beranda.
     */
    public function getDashboardData(): array
    {
        $cache = \Config\Services::cache();
        
        // 1. Cache total alumni selama 15 menit
        $totalAlumni = $cache->get('alumni_count');
        if ($totalAlumni === null) {
            $totalAlumni = $this->userModel->countAllResults();
            $cache->save('alumni_count', $totalAlumni, 900);
        }

        // 2. Hitung persebaran (proxy: jumlah kota/tempat lahir unik)
        $totalProvinsi = $this->db->table('users')->selectCount('tempat_lahir', 'count')->distinct()->get()->getRow()->count;

        // 3. Ambil 2 member Syndicate terbaru sebagai representasi eksekutif
        $kuratorDb = $this->db->table('syndicate')
            ->select('users.nama_panggilan, syndicate.kategori as jabatan, users.foto_profil')
            ->join('users', 'users.id = syndicate.user_id')
            ->orderBy('syndicate.created_at', 'DESC')
            ->limit(2)
            ->get()->getResultArray();

        $kuratorList = [];
        if (!empty($kuratorDb)) {
            foreach ($kuratorDb as $k) {
                $kuratorList[] = [
                    'nama'    => $k['nama_panggilan'],
                    'jabatan' => 'Direktur ' . $k['jabatan'],
                    'foto'    => !empty($k['foto_profil']) ? '/uploads/profiles/' . $k['foto_profil'] : '/images/ornamen-bawah-emas.png'
                ];
            }
        } else {
            // Fallback jika belum ada data bisnis
            $kuratorList = [
                ['nama' => 'Sang Komandan', 'jabatan' => 'Ketua Syndicate', 'foto' => '/images/ornamen-bawah-emas.png'],
                ['nama' => 'Juru Kunci', 'jabatan' => 'Sekretaris Eksekutif', 'foto' => '/images/segi-delapan-perak.png']
            ];
        }

        // 4. Query ulang tahun — tanpa cache untuk akurasi harian
        $time = \CodeIgniter\I18n\Time::now('Asia/Jakarta');
        $todayMonthDay = $time->format('m-d'); // Format MM-DD
        
        try {
            $birthdayUsers = $this->userModel
                ->where('birth_month_day', $todayMonthDay)
                ->findAll();
        } catch (\Exception $e) {
            // Fallback ke method lama jika kolom belum ada (pre-migration)
            $birthdayUsers = $this->userModel
                ->where('MONTH(tanggal_lahir)', $time->format('m'))
                ->where('DAY(tanggal_lahir)', $time->format('d'))
                ->findAll();
        }

        // 5. Leaderboard Prestise (Top 5 Jajaran Kehormatan)
        $leaderboard = $this->db->table('users')
            ->select('nama_lengkap, nama_panggilan, foto_profil, prestise_points')
            ->where('prestise_points >', 0) // Hanya tampilkan yang punya poin
            ->orderBy('prestise_points', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 6. Berita/Pengumuman dinamis dari database
        $announcementModel = new AnnouncementModel();
        $beritaDb = $announcementModel->getBeritaBeranda(5);
        $beritaList = [];
        foreach ($beritaDb as $b) {
            $beritaList[] = [
                'tanggal' => date('d M Y', strtotime($b['published_at'])),
                'judul'   => $b['title'],
                'konten'  => $b['content'],
                'slug'    => '#',
            ];
        }
        // Fallback jika belum ada data
        if (empty($beritaList)) {
            $beritaList = [
                ['tanggal' => '12 Mar ' . date('Y'), 'judul' => 'Proyek Lagu Angkatan Resmi Dimulai', 'konten' => '', 'slug' => '#'],
                ['tanggal' => '05 Feb ' . date('Y'), 'judul' => 'Pemilihan Jajaran Kurator Syndicate', 'konten' => '', 'slug' => '#'],
            ];
        }

        return [
            'total_alumni'      => $totalAlumni,
            'total_provinsi'    => $totalProvinsi, 
            'tahun_kebangkitan' => date('Y'),
            
            // Lorong Kenangan
            'galeri' => [
                ['file' => '/images/globe.png', 'caption' => 'Memori Pertama'],
                ['file' => '/images/cincin-emas.png', 'caption' => 'Ikatan Persaudaraan'],
                ['file' => '/images/mahkota-emas.png', 'caption' => 'Amanah Kepemimpinan'],
                ['file' => '/images/kristal-puncak.png', 'caption' => 'Visi Puncak'],
            ],

            // Berita dinamis dari database
            'berita' => $beritaList,

            'kurator' => $kuratorList,

            // Ide 8: Garis Waktu
            'timeline' => [
                ['tahun' => '2020', 'deskripsi' => 'Pijakan pertama di tanah Arrisalah. Langkah awal sebuah epik.'],
                ['tahun' => '2026', 'deskripsi' => 'Ekspedisi dimulai. Kami menyebar ke seluruh penjuru sebagai pelopor.'],
            ],

            'birthday_users' => $birthdayUsers,
            'leaderboard'    => $leaderboard
        ];
    }

    /**
     * Menyimpan pesan buku tamu.
     */
    public function saveGuestBookMessage(string $nama, string $pesan): void
    {
        $this->bukuTamuModel->insert([
            'nama'       => $nama, 
            'pesan'      => $pesan,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
