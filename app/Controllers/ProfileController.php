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

    /**
     * Mengubah kata sandi dari halaman profil.
     */
    public function changePassword()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/login');

        $rules = [
            'current_password'  => 'required',
            'new_password'      => 'required|min_length[8]',
            'confirm_password'  => 'required|matches[new_password]'
        ];

        $messages = [
            'new_password' => [
                'min_length' => 'Kata sandi baru minimal 8 karakter.'
            ],
            'confirm_password' => [
                'matches' => 'Konfirmasi kata sandi tidak cocok.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/profil')->with('error', implode(' ', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Verifikasi kata sandi lama
        if (!password_verify($this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->to('/profil')->with('error', 'Kata sandi lama yang Anda masukkan salah.');
        }

        // Update hash password baru
        $userModel->update($userId, [
            'password_hash' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/profil')->with('pesan', 'Kata sandi berhasil diperbarui!');
    }

    /**
     * Fitur Soft Delete Akun
     */
    public function deleteAccount()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/login');

        // Verify password before deleting
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $password = $this->request->getPost('password_delete');

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->to('/profil')->with('error', 'Kata sandi salah. Penghapusan akun dibatalkan.');
        }

        // Soft delete: set is_active to 0
        $userModel->update($userId, ['is_active' => 0]); 
        
        session()->destroy();
        return redirect()->to('/login')->with('pesan', 'Akun Anda telah dinonaktifkan secara permanen dari sistem.');
    }
}