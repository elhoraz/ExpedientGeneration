<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
        }

        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));
        
        $faceData = empty($user['face_data']) ? 'null' : $user['face_data'];

        $data = [
            'user' => $user,
            'face_data_db' => $faceData
        ];

        return view('profil', $data);
    }

    public function updateProfile()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $userModel = new UserModel();
        $userId = session()->get('user_id');
        
        // Aturan validasi (bisa disesuaikan jika perlu)
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

        // Proses Foto Profil Base64 jika ada perubahan
        $fotoBase64 = $this->request->getPost('foto_profil_base64');
        if (!empty($fotoBase64)) {
            $imageParts = explode(";base64,", $fotoBase64);
            if (count($imageParts) == 2) {
                $imageBase64 = base64_decode($imageParts[1]);
                $namaFileFoto = uniqid('expedient_') . '.jpg';
                $path = FCPATH . 'uploads/profiles/' . $namaFileFoto;
                file_put_contents($path, $imageBase64);
                $dataUpdate['foto_profil'] = $namaFileFoto;
            }
        }

        $userModel->update($userId, $dataUpdate);

        // Update session jika nama/email berubah
        session()->set([
            'nama_panggilan' => $dataUpdate['nama_panggilan'],
            'email'          => $dataUpdate['email']
        ]);

        return redirect()->to('/profil')->with('pesan', 'Data entitas berhasil diperbarui!');
    }
}