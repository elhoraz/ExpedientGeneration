<?php

namespace App\Services;

use App\Models\UserModel;
use App\Libraries\ActivityLogger;
use App\Services\EmailQueueService;

/**
 * AuthService
 * 
 * Memisahkan business logic otentikasi dari AuthController.
 * Controller hanya menangani HTTP request/response,
 * sementara service ini menangani logika bisnis murni.
 * 
 * Usage:
 *   $authService = new AuthService();
 *   $user = $authService->attemptLogin($email, $password);
 */
class AuthService
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Coba login dengan email dan password.
     * 
     * @param string $email
     * @param string $password
     * @return array|null Data user jika berhasil, null jika gagal
     * @throws \Exception Jika email belum diverifikasi
     */
    public function attemptLogin(string $email, string $password): ?array
    {
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            ActivityLogger::log('LOGIN_FAILED', "Email tidak ditemukan: {$email}");
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            ActivityLogger::log('LOGIN_FAILED', "Password salah untuk: {$email}");
            return null;
        }

        // Cek verifikasi email
        if (is_null($user['email_verified_at'])) {
            throw new \Exception('Email belum diverifikasi.');
        }

        // Cek apakah akun sudah dinonaktifkan oleh admin
        if (isset($user['is_active']) && $user['is_active'] == 0) {
            throw new \Exception('Akun Anda telah dinonaktifkan.');
        }

        ActivityLogger::log('LOGIN_SUCCESS', "Login berhasil: {$email}", $user['id']);
        return $user;
    }

    /**
     * Registrasi user baru.
     * 
     * @param array $data Data user dari form
     * @return int ID user yang baru dibuat
     * @throws \Exception Jika gagal
     */
    public function registerUser(array $data): int
    {
        // Auto-generate birth_month_day untuk query birthday yang ter-index
        if (!empty($data['tanggal_lahir'])) {
            $data['birth_month_day'] = date('m-d', strtotime($data['tanggal_lahir']));
        }

        // Generate token verifikasi
        $token = bin2hex(random_bytes(32));
        $data['email_verify_token'] = $token;

        $this->userModel->insert($data);
        $userId = $this->userModel->getInsertID();
        
        $data['id'] = $userId; // For email

        ActivityLogger::log('REGISTER', "Registrasi berhasil: {$data['email']}", $userId);

        // Invalidate cache
        \Config\Services::cache()->delete('alumni_count');
        \Config\Services::cache()->delete('alumni_list');
        
        // Kirim email verifikasi
        $this->sendVerificationEmail($data, $token);

        return $userId;
    }

    /**
     * Kirim email verifikasi.
     * 
     * @param array $user Data user
     * @param string $token Token verifikasi
     * @return bool
     */
    public function sendVerificationEmail(array $user, string $token): bool
    {
        $linkVerifikasi = base_url('auth/verify/' . $token);
        $pesan = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #d4af37; border-radius: 10px; background-color: #0d0d0d; color: #e0e0e0;'>
                <h2 style='color: #ffd700; text-align: center;'>Selamat Datang di Keluarga Besar Expedient!</h2>
                <p>Halo <strong>{$user['nama_panggilan']}</strong>,</p>
                <p>Pendaftaran Anda telah kami terima. Silakan verifikasi email Anda dengan mengklik tautan di bawah ini:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$linkVerifikasi}' style='background-color: #d4af37; color: #111; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verifikasi Akun Saya</a>
                </div>
                <p style='text-align: center; margin-top: 40px; border-top: 1px solid #333; padding-top: 20px;'>42nd Arrisalah Expedient Generation</p>
            </div>
        ";

        try {
            $queue = new EmailQueueService();
            $queue->enqueue($user['email'], 'Verifikasi Akun - Expedient 42nd Arrisalah', $pesan, $user['nama_panggilan'] ?? '');
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Email Queue Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate dan kirim kode reset password.
     * 
     * @param string $email
     * @return string|false Kode reset jika berhasil, false jika email tidak ditemukan
     */
    public function sendResetCode(string $email): bool
    {
        $user = $this->userModel->where('email', $email)->first();
        if (!$user) return false;

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $this->userModel->update($user['id'], [
            'reset_password_code' => $code,
            'reset_password_expires' => $expires,
        ]);

        ActivityLogger::log('PASSWORD_RESET_REQUEST', "Kode reset dikirim ke: {$email}", $user['id']);

        $emailService = new EmailQueueService();

        $pesan = "
            <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:20px;border:1px solid #d4af37;border-radius:10px;background:#0d0d0d;color:#e0e0e0;'>
                <h2 style='color:#ffd700;text-align:center;'>Reset Kata Sandi</h2>
                <p>Halo <strong>{$user['nama_panggilan']}</strong>,</p>
                <p>Berikut adalah kode verifikasi Anda:</p>
                <div style='text-align:center;margin:30px 0;'>
                    <span style='background:#d4af37;color:#111;padding:15px 30px;font-size:28px;font-weight:bold;letter-spacing:8px;border-radius:8px;display:inline-block;'>{$code}</span>
                </div>
                <p style='color:#999;text-align:center;'>Kode berlaku selama 15 menit.</p>
                <p style='text-align:center;margin-top:40px;border-top:1px solid #333;padding-top:20px;'>42nd Arrisalah Expedient Generation</p>
            </div>";

        try {
            $emailService->enqueue($email, 'Kode Reset Sandi - Expedient Generation', $pesan, $user['nama_panggilan'] ?? '');
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Email Queue Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifikasi kode reset password.
     * 
     * @param string $email
     * @param string $code
     * @return bool
     */
    public function verifyResetCode(string $email, string $code): bool
    {
        $user = $this->userModel
            ->where('email', $email)
            ->where('reset_password_code', $code)
            ->where('reset_password_expires >', date('Y-m-d H:i:s'))
            ->first();

        return $user !== null;
    }

    /**
     * Reset password user.
     * 
     * @param string $email
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword(string $email, string $newPassword): bool
    {
        $user = $this->userModel->where('email', $email)->first();
        if (!$user) return false;

        $this->userModel->update($user['id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_password_code' => null,
            'reset_password_expires' => null,
        ]);

        ActivityLogger::log('PASSWORD_RESET', "Password di-reset untuk: {$email}", $user['id']);
        return true;
    }
}
