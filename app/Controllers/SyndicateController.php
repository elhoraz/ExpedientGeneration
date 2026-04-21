<?php

namespace App\Controllers;

use App\Models\SyndicateModel;

class SyndicateController extends BaseController
{
    protected $syndicateModel;

    public function __construct()
    {
        // Panggil helper URL agar function base_url() dkk aktif
        helper(['url', 'form']);
        $this->syndicateModel = new SyndicateModel();
    }

    public function index()
    {
        // Query untuk menggabungkan (JOIN) data bisnis dengan data USER (Foto Profil)
        $db = \Config\Database::connect();
        $builder = $db->table('syndicate');
        $builder->select('syndicate.*, users.nama_panggilan, users.no_whatsapp, users.foto_profil');
        $builder->join('users', 'users.id = syndicate.user_id');
        $builder->orderBy('syndicate.created_at', 'DESC');
        
        $data['portofolio'] = $builder->get()->getResultArray();

        return view('syndicate/index', $data);
    }

    /**
     * Menampilkan Formulir Pendaftaran VIP
     */
    public function create()
    {
        // Pastikan user sudah login sebelum membuka formulir ini
        // (Sistem login harus sudah aktif, jika memakai filter 'auth')
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Otorisasi gagal. Silakan login terlebih dahulu.');
        }

        return view('syndicate/create');
    }

    /**
     * Memproses Penyimpanan Data ke Ledger Pusat
     */
    public function store()
    {
        // 1. Ambil ID Agen dari Session
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Otorisasi gagal. Sesi habis.');
        }

        // 2. TENTUKAN ATURAN VALIDASI KETAT
        $rules = [
            'nama_bisnis' => 'required|min_length[3]|max_length[150]',
            'kategori'    => 'required|in_list[F&B,Teknologi,Jasa,Kreatif,Retail]', // Validasi harus select satu dari list
            'deskripsi'   => 'required|min_length[10]|max_length[200]',
            'link_url'    => 'permit_empty|valid_url',
            'logo_bisnis' => 'permit_empty|is_image[logo_bisnis]|mime_in[logo_bisnis,image/jpg,image/jpeg,image/png]|max_size[logo_bisnis,2048]'
        ];

        // Terapkan pesan error khusus (Opsional agar user tidak bingung)
        $messages = [
            'logo_bisnis' => [
                'max_size' => 'Ukuran logo terlalu besar. Maksimal 2MB Kapten.',
                'mime_in'  => 'Hanya file JPG, JPEG, atau PNG yang diizinkan.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            // Kembali ke form dan bawa error serta input lama
            return redirect()->to('/syndicate/create')->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // 3. PROSES UNGGAH LOGO BISNIS
        $fileLogo = $this->request->getFile('logo_bisnis');
        $namaFileLogo = null; // Default null jika tidak ada logo

        if ($fileLogo->isValid() && !$fileLogo->hasMoved()) {
            // Beri nama unik (Syndicate_randomstring.jpg)
            $namaFileLogo = $fileLogo->getRandomName();
            
            // Pindahkan ke folder public/uploads/bisnis/
            // Pastikan folder ini sudah Anda buat manual di server
            $fileLogo->move(FCPATH . 'uploads/bisnis/', $namaFileLogo);
        }

        // 4. SIMPAN KE DATABASE Lewat Model
        $this->syndicateModel->save([
            'user_id'     => $userId,
            'nama_bisnis' => $this->request->getPost('nama_bisnis'),
            'kategori'    => $this->request->getPost('kategori'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'link_url'    => $this->request->getPost('link_url'),
            'logo_bisnis' => $namaFileLogo, // Simpan nama filenya saja
        ]);

        // 5. REDIRECT KEMBALI KE GALERI JARINGAN DENGAN SUKSES
        return redirect()->to('/syndicate')->with('success', 'Arsip Jaringan Bisnis Anda telah terdaftar di Ledger Pusat. Selamat berkolaborasi!');
    }
}