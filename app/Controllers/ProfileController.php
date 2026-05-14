<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\ProfileService;
use App\Services\GamificationService;

class ProfileController extends BaseController
{
    protected ProfileService $profileService;

    public function __construct()
    {
        $this->profileService = new ProfileService();
    }

    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));
        
        $faceData = empty($user['face_data']) ? 'null' : $user['face_data'];

        $gamificationService = new GamificationService();
        $prestisePoints = $user['prestise_points'] ?? 0;
        
        $data = [
            'user' => $user,
            'face_data_db' => $faceData,
            'prestise_points' => $prestisePoints,
            'gelar_kehormatan' => $gamificationService->getGelar($prestisePoints),
            'badge_color'      => $gamificationService->getBadgeColor($prestisePoints)
        ];

        return view('profil', $data);
    }

    public function updateProfile()
    {
        $userId = session()->get('user_id');
        
        // Aturan validasi
        $rules = [
            'nama_lengkap'         => 'required|min_length[3]',
            'nama_panggilan'       => 'required|min_length[2]',
            'email'                => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'no_whatsapp'          => 'required|numeric|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/profil')->withInput()->with('error', 'Terjadi kesalahan validasi data. Periksa kembali isian Anda.');
        }

        $dataUpdate = [
            'nama_lengkap'   => $this->request->getPost('nama_lengkap'),
            'nama_panggilan' => $this->request->getPost('nama_panggilan'),
            'email'          => $this->request->getPost('email'),
            'no_whatsapp'    => $this->request->getPost('no_whatsapp'),
            'motivasi_hidup' => $this->request->getPost('motivasi_hidup'),
            'cita_cita'      => $this->request->getPost('cita_cita'),
            'akun_ig'        => $this->request->getPost('akun_ig'),
            'akun_tiktok'    => $this->request->getPost('akun_tiktok'),
        ];

        // Proses Foto Profil Base64 menggunakan ProfileService
        $fotoBase64 = $this->request->getPost('foto_profil_base64');
        if (!empty($fotoBase64)) {
            try {
                $filename = $this->profileService->processProfilePhoto($fotoBase64, $userId);
                if ($filename) {
                    $dataUpdate['foto_profil'] = $filename;
                }
            } catch (\InvalidArgumentException $e) {
                return redirect()->to('/profil')->with('error', $e->getMessage());
            }
        }

        $this->profileService->updateProfile($userId, $dataUpdate);

        // Update session jika nama/email berubah
        session()->set([
            'nama_panggilan' => $dataUpdate['nama_panggilan'],
            'email'          => $dataUpdate['email']
        ]);

        return redirect()->to('/profil')->with('pesan', 'Data entitas berhasil diperbarui!');
    }
}