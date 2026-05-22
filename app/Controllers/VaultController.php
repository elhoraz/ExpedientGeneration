<?php

namespace App\Controllers;

use App\Models\UserModel;

class VaultController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); 
    }

    /**
     * Resolve user by public_token instead of auto-increment ID.
     * Prevents IDOR (Insecure Direct Object Reference) attacks.
     */
    protected function findByToken(string $token): ?array
    {
        $user = $this->userModel->where('public_token', $token)->first();
        return $user ?: null;
    }

    // ==========================================
    // 1. HALAMAN PROFIL (Dossier Kaca 3D)
    // ==========================================
    public function profil($token)
    {
        $user = $this->findByToken($token);

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
    public function scan_gateway($token)
    {
        $user = $this->findByToken($token);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Entitas tidak ditemukan.');
        }

        $data['user'] = $user;
        return view('scan_gateway', $data); 
    }
	
    public function ar_hologram($token)
    {
        $user = $this->findByToken($token);

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
    public function download_vcard($token)
    {
        $user = $this->findByToken($token);

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
        $vcard .= "URL:" . base_url('profil/' . $user['public_token']) . "\r\n";
        $vcard .= "END:VCARD\r\n";

        return $this->response->download($user['nama_panggilan'] . '_Expedient.vcf', $vcard)
                              ->setContentType('text/vcard');
    }

    // ==========================================
    // 4. IN-APP QR SCANNER
    // ==========================================
    public function scanner()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
        }

        return view('scanner');
    }
}
