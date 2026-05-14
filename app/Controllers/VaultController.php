<?php

namespace App\Controllers;

use App\Models\UserModel;

class VaultController extends BaseController
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
            'foto_profil' => !empty($user['foto_profil']) ? base_url('uploads/profiles/' . $user['foto_profil']) : ''
        ];

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

        $fotoUrl = '';
        if (!empty($user['foto_profil'])) {
            $fotoUrl = base_url('uploads/profiles/' . $user['foto_profil']);
        }

        $data = [
            'user' => $user,
            'foto_profil' => $fotoUrl
        ];

        return view('ar_hologram', $data); 
    }

    // ==========================================
    // 3. GENERATE VCARD OTOMATIS (Opsi 2)
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
        $vcard .= "TITLE:Sovereign Member\r\n"; 
        $vcard .= "ORG:Expedient Generation\r\n";
        $vcard .= "TEL;TYPE=WORK,VOICE:" . $user['no_whatsapp'] . "\r\n";
        $vcard .= "EMAIL:" . $user['email'] . "\r\n";
        $vcard .= "URL:" . base_url('profil/' . $user['id']) . "\r\n";
        $vcard .= "END:VCARD\r\n";

        return $this->response->download($user['nama_panggilan'] . '_Expedient.vcf', $vcard)
                              ->setContentType('text/vcard');
    }
}
