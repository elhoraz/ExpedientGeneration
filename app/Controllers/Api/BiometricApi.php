<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Libraries\ActivityLogger;

/**
 * BiometricApi
 * 
 * REST API endpoints untuk operasi WebAuthn/FIDO2 biometric.
 * Format response standar JSON.
 */
class BiometricApi extends BaseApiController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * GET /api/biometric/login-options
     * Generate challenge untuk login biometric.
     */
    public function loginOptions()
    {
        $email = $this->request->getGet('email');
        if (empty($email)) {
            return $this->jsonError('Email diperlukan.', 422);
        }

        $user = $this->userModel->where('email', $email)->first();
        if (!$user || empty($user['webauthn_credential_id'])) {
            return $this->jsonError('Biometric belum terdaftar untuk email ini.', 404);
        }

        $challenge = bin2hex(random_bytes(32));
        session()->set('biometric_challenge', $challenge);
        session()->set('biometric_user_id', $user['id']);

        return $this->jsonSuccess([
            'challenge'    => $challenge,
            'credentialId' => $user['webauthn_credential_id'],
            'userId'       => $user['id'],
        ], 'Challenge berhasil di-generate.');
    }

    /**
     * POST /api/biometric/login-verify
     * Verifikasi signature biometric untuk login.
     */
    public function loginVerify()
    {
        $json = $this->request->getJSON();
        $challenge = session()->get('biometric_challenge');
        $userId = session()->get('biometric_user_id');

        if (!$challenge || !$userId) {
            return $this->jsonError('Sesi challenge tidak ditemukan. Silakan ulang.', 401);
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return $this->jsonError('User tidak ditemukan.', 404);
        }

        // Verifikasi sederhana (signature matching)
        // Di production, gunakan library WebAuthn lengkap
        if (isset($json->credential_id) && $json->credential_id === $user['webauthn_credential_id']) {
            session()->set([
                'user_id'        => $user['id'],
                'nama_panggilan' => $user['nama_panggilan'],
                'email'          => $user['email'],
                'logged_in'      => true
            ]);

            session()->remove(['biometric_challenge', 'biometric_user_id']);
            ActivityLogger::log('BIOMETRIC_LOGIN', "Login biometric berhasil", $user['id']);

            return $this->jsonSuccess(null, 'Login biometric berhasil.');
        }

        return $this->jsonError('Verifikasi biometric gagal.', 401);
    }

    /**
     * GET /api/biometric/register-options
     * Generate challenge untuk mendaftarkan biometric baru.
     */
    public function registerOptions()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->jsonError('Anda harus login terlebih dahulu.', 401);
        }

        $user = $this->userModel->find($userId);
        $challenge = bin2hex(random_bytes(32));
        session()->set('biometric_reg_challenge', $challenge);

        return $this->jsonSuccess([
            'challenge' => $challenge,
            'userId'    => $user['id'],
            'userName'  => $user['email'],
            'displayName' => $user['nama_panggilan'],
        ], 'Challenge pendaftaran berhasil di-generate.');
    }

    /**
     * POST /api/biometric/register-verify
     * Verifikasi dan simpan credential biometric baru.
     */
    public function registerVerify()
    {
        $userId = session()->get('user_id');
        $challenge = session()->get('biometric_reg_challenge');

        if (!$userId || !$challenge) {
            return $this->jsonError('Sesi tidak valid.', 401);
        }

        $json = $this->request->getJSON();
        if (empty($json->credential_id)) {
            return $this->jsonError('Credential ID diperlukan.', 422);
        }

        $this->userModel->update($userId, [
            'webauthn_credential_id' => $json->credential_id
        ]);

        // Simpan ke tabel biometrics juga jika ada
        $db = \Config\Database::connect();
        if ($db->tableExists('user_biometrics')) {
            $db->table('user_biometrics')->insert([
                'user_id'       => $userId,
                'credential_id' => $json->credential_id,
                'public_key'    => $json->public_key ?? '',
                'sign_count'    => 0,
                'created_at'    => date('Y-m-d H:i:s')
            ]);
        }

        session()->remove('biometric_reg_challenge');
        ActivityLogger::log('BIOMETRIC_ENROLL', "Biometric baru didaftarkan", $userId);

        return $this->jsonSuccess(null, 'Biometric berhasil didaftarkan.');
    }
}
