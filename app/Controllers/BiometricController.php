<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class BiometricController extends BaseController
{
    // ========================================================================
    // FUNGSI HELPER KRIPTOGRAFI MURNI (Mencegah Error Library)
    // ========================================================================
    private function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // ========================================================================
    // BAGIAN 1: PROSES LOGIN MENGGUNAKAN BIOMETRIK (DARI HALAMAN DEPAN)
    // ========================================================================
    public function getLoginOptions()
    {
        // Ganti 'localhost' dengan domain asli Anda jika sudah online (misal: expedient.id)
        $rpId = 'localhost'; 

        // 1. Buat Challenge Acak
        $challenge = random_bytes(32);
        
        // Simpan challenge (format Base64Url) ke session
        session()->set('webauthn_login_challenge', $this->base64url_encode($challenge));

        // 2. Buat Opsi Permintaan (Manual Array, 100% Kebal Error)
        $options = [
            'challenge' => $this->base64url_encode($challenge),
            'rpId'      => $rpId,
            'userVerification' => 'preferred',
            'timeout'   => 60000
        ];

        return $this->response->setJSON($options);
    }

    public function loginVerify()
    {
        $json = $this->request->getJSON();
        if (!$json) return $this->response->setJSON(['error' => 'Data biometrik tidak valid atau kosong.']);

        $savedChallenge = session()->get('webauthn_login_challenge');
        if (!$savedChallenge) return $this->response->setJSON(['error' => 'Sesi biometrik kadaluarsa. Silakan refresh halaman.']);

        $credentialId = $json->rawId ?? null;
        if (!$credentialId) return $this->response->setJSON(['error' => 'Gagal membaca ID Sensor Anda.']);

        $userModel = new UserModel();
        $user = $userModel->where('webauthn_credential_id', $credentialId)->first();

        if ($user) {
            session()->remove('webauthn_login_challenge');

            // Cek apakah emailnya sudah diverifikasi
            if (array_key_exists('email_verified_at', $user) && is_null($user['email_verified_at'])) {
                return $this->response->setJSON(['error' => 'Akun belum diverifikasi. Silakan cek email Anda.']);
            }

            $session = session();
            $sesData = [
                'user_id'         => $user['id'],
                'nama_panggilan'  => $user['nama_panggilan'],
                'email'           => $user['email'],
                'logged_in'       => TRUE
            ];
            $session->set($sesData);

            return $this->response->setJSON([
                'status' => 'success', 
                'redirect' => base_url('/beranda') 
            ]);
        } else {
            return $this->response->setJSON(['error' => 'Sidik jari / Wajah tidak dikenali di sistem kami.']);
        }
    }

    // ========================================================================
    // BAGIAN 2: PROSES MENDAFTARKAN BIOMETRIK (DARI PROFIL)
    // ========================================================================
    public function getRegisterOptions()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['error' => 'Sesi login tidak ditemukan.']);

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        $challenge = random_bytes(32);
        session()->set('webauthn_register_challenge', $this->base64url_encode($challenge));

        // Buat Opsi Registrasi (Manual Array, Format FIDO2 Murni)
        $options = [
            'challenge' => $this->base64url_encode($challenge),
            'rp' => [
                'name' => 'Expedient Generation 42nd',
                'id'   => 'localhost' // Ganti dengan domain asli
            ],
            'user' => [
                'id'          => $this->base64url_encode((string)$user['id']),
                'name'        => $user['email'],
                'displayName' => $user['nama_panggilan']
            ],
            'pubKeyCredParams' => [
                ['type' => 'public-key', 'alg' => -7],  // ES256 (Apple/Android)
                ['type' => 'public-key', 'alg' => -257] // RS256 (Windows Hello)
            ],
            'authenticatorSelection' => [
                'authenticatorAttachment' => 'platform',
                'userVerification' => 'preferred'
            ],
            'timeout' => 60000,
            'attestation' => 'none'
        ];

        return $this->response->setJSON($options);
    }

    public function registerVerify()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['error' => 'Akses ditolak. Sesi login tidak ditemukan.']);

        $json = $this->request->getJSON();
        if (!$json) return $this->response->setJSON(['error' => 'Data tidak valid.']);

        $savedChallenge = session()->get('webauthn_register_challenge');
        if (!$savedChallenge) return $this->response->setJSON(['error' => 'Sesi pendaftaran kadaluarsa. Coba lagi.']);

        $credentialId = $json->rawId ?? null;

        if ($credentialId) {
            $userModel = new UserModel();
            $userModel->update($userId, [
                'webauthn_credential_id' => $credentialId
            ]);

            session()->remove('webauthn_register_challenge');

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Sensor biometrik berhasil ditautkan ke akun Anda!'
            ]);
        } else {
            return $this->response->setJSON(['error' => 'Gagal membaca sensor perangkat Anda.']);
        }
    }
}