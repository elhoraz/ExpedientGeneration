<?php

namespace App\Controllers;

use App\Models\UserModel; // Pastikan Kapten sudah punya model database ini

class Vault extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        // Inisialisasi model database
        $this->userModel = new UserModel(); 
    }

    // ==========================================
    // 1. HALAMAN PROFIL (Dossier Kaca 3D)
    // ==========================================
    public function profil($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data Sovereign tidak ditemukan.');
        }

        $data = [
            'user' => $user,
            // DISESUAIKAN: Menggunakan kolom 'foto_profil' sesuai database
            'foto_profil' => !empty($user['foto_profil']) ? base_url('uploads/profil/' . $user['foto_profil']) : ''
        ];

        // Memanggil file view yang berisi kode efek kaca 3D tadi
        return view('profil_dossier', $data); 
    }

    // ==========================================
    // 2. GATEWAY POP-UP SAAT SCAN QR
    // ==========================================
   public function scan_gateway($id)
    {
        $data['user'] = $this->userModel->find($id);
        return view('scan_gateway', $data); 
    }
	
	public function ar_hologram($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Entitas tidak ditemukan.');
        }

        // PAKSA LOAD URL SEPERTI KODE KAPTEN
        $fotoUrl = '';
        if (!empty($user['foto_profil'])) {
            // PERHATIKAN: Foldernya 'profiles', bukan 'profil'
            $fotoUrl = base_url('uploads/profiles/' . $user['foto_profil']);
        }

        $data = [
            'user' => $user,
            'foto_profil' => $fotoUrl
        ];

        return view('ar_hologram', $data); 
    }

    // ==========================================
    // 3. FITUR REGISTRASI
    // ==========================================
    public function register()
    {
        // Menampilkan form pendaftaran
        return view('register_sovereign');
    }

    public function simpan_register()
    {
        // DISESUAIKAN: Kunci array disinkronkan MUTLAK dengan $allowedFields di UserModel
        // Catatan form HTML Kapten name="..." nya juga harus menyesuaikan ini
        $data = [
            'nama_lengkap'   => $this->request->getPost('nama_lengkap'),
            'nama_panggilan' => $this->request->getPost('nama_panggilan'),
            'email'          => $this->request->getPost('email'),
            'no_whatsapp'    => $this->request->getPost('no_whatsapp'), // Diubah dari no_wa
            'motivasi_hidup' => $this->request->getPost('motivasi_hidup'), // Diubah dari quote
            
            // Kolom 'jabatan' saya hapus dari sini karena tidak ada di UserModel
        ];

        // Simpan ke database
        $this->userModel->insert($data);

        // Arahkan ke halaman login atau langsung ke Vault
        return redirect()->to(base_url('login'))->with('pesan', 'Akses Sovereign berhasil dibuat!');
    }

    // ==========================================
    // 4. GENERATE VCARD OTOMATIS (Opsi 2)
    // ==========================================
    public function download_vcard($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "FN:" . $user['nama_lengkap'] . "\r\n";
        // DISESUAIKAN: Karena 'jabatan' tidak ada di database, menggunakan gelar eksklusif default
        $vcard .= "TITLE:Sovereign Member\r\n"; 
        $vcard .= "ORG:Expedient Generation\r\n";
        // DISESUAIKAN: Menggunakan kolom 'no_whatsapp'
        $vcard .= "TEL;TYPE=WORK,VOICE:" . $user['no_whatsapp'] . "\r\n";
        $vcard .= "EMAIL:" . $user['email'] . "\r\n";
        $vcard .= "URL:" . base_url('profil/' . $user['id']) . "\r\n";
        $vcard .= "END:VCARD\r\n";

        return $this->response->download($user['nama_panggilan'] . '_Expedient.vcf', $vcard)
                              ->setContentType('text/vcard');
    }
}