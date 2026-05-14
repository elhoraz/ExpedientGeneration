<?php

namespace App\Controllers;

use App\Models\SyndicateModel;
use App\Services\SyndicateService;

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
        // Gunakan scope withUser() dari model untuk menggabungkan data user
        $data['portofolio'] = $this->syndicateModel->withUser()
            ->orderBy('syndicate.created_at', 'DESC')
            ->paginate(12);
        $data['pager'] = $this->syndicateModel->pager;

        return view('syndicate/index', $data);
    }

    /**
     * Menampilkan Formulir Pendaftaran VIP
     */
    public function create()
    {


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

        $syndicateService = new SyndicateService();
        $syndicateService->storeBisnis(
            $userId, 
            [
                'nama_bisnis' => $this->request->getPost('nama_bisnis'),
                'kategori'    => $this->request->getPost('kategori'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'link_url'    => $this->request->getPost('link_url'),
            ], 
            $this->request->getFile('logo_bisnis')
        );

        // 5. REDIRECT KEMBALI KE GALERI JARINGAN DENGAN SUKSES
        return redirect()->to('/syndicate')->with('success', 'Arsip Jaringan Bisnis Anda telah terdaftar di Ledger Pusat. Selamat berkolaborasi!');
    }

    /**
     * Menampilkan Formulir Edit VIP
     */
    public function edit($id)
    {
        $userId = session()->get('user_id');
        $bisnis = $this->syndicateModel->find($id);

        if (!$bisnis || $bisnis['user_id'] != $userId) {
            return redirect()->to('/syndicate')->with('error', 'Otorisasi gagal. Anda tidak memiliki akses untuk mengubah data ini.');
        }

        $data['bisnis'] = $bisnis;
        return view('syndicate/edit', $data);
    }

    /**
     * Memproses Pembaruan Data
     */
    public function update($id)
    {
        $userId = session()->get('user_id');
        $bisnis = $this->syndicateModel->find($id);

        if (!$bisnis || $bisnis['user_id'] != $userId) {
            return redirect()->to('/syndicate')->with('error', 'Otorisasi gagal.');
        }

        $rules = [
            'nama_bisnis' => 'required|min_length[3]|max_length[150]',
            'kategori'    => 'required|in_list[F&B,Teknologi,Jasa,Kreatif,Retail]',
            'deskripsi'   => 'required|min_length[10]|max_length[200]',
            'link_url'    => 'permit_empty|valid_url',
            'logo_bisnis' => 'permit_empty|is_image[logo_bisnis]|mime_in[logo_bisnis,image/jpg,image/jpeg,image/png]|max_size[logo_bisnis,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to("/syndicate/edit/{$id}")->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama_bisnis' => $this->request->getPost('nama_bisnis'),
            'kategori'    => $this->request->getPost('kategori'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'link_url'    => $this->request->getPost('link_url'),
        ];

        $syndicateService = new SyndicateService();
        try {
            $syndicateService->updateBisnis($id, $userId, $dataUpdate, $this->request->getFile('logo_bisnis'));
            return redirect()->to('/syndicate')->with('success', 'Data bisnis berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->to('/syndicate')->with('error', $e->getMessage());
        }
    }

    /**
     * Memproses Penghapusan Data
     */
    public function delete($id)
    {
        $userId = session()->get('user_id');
        $bisnis = $this->syndicateModel->find($id);

        if (!$bisnis || $bisnis['user_id'] != $userId) {
            return redirect()->to('/syndicate')->with('error', 'Otorisasi gagal.');
        }

        $syndicateService = new SyndicateService();
        try {
            $syndicateService->deleteBisnis($id, $userId);
            return redirect()->to('/syndicate')->with('success', 'Data bisnis berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/syndicate')->with('error', $e->getMessage());
        }
    }
}