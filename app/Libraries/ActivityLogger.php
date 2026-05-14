<?php

namespace App\Libraries;

/**
 * ActivityLogger
 * 
 * Mencatat semua aktivitas penting pengguna ke file log terpisah
 * di writable/logs/activity-YYYY-MM-DD.log.
 * 
 * Format: [TIMESTAMP] [USER:ID] [ACTION] [IP:ADDRESS] Detail
 * 
 * Usage:
 *   ActivityLogger::log('LOGIN_SUCCESS', 'Login berhasil via email', $userId);
 *   ActivityLogger::log('PROFILE_UPDATE', 'Foto profil diubah');
 */
class ActivityLogger
{
    /**
     * Mencatat aktivitas ke file log harian.
     *
     * @param string   $action  Nama aksi (LOGIN_SUCCESS, REGISTER, PROFILE_UPDATE, dll)
     * @param string   $detail  Detail tambahan tentang aksi
     * @param int|null $userId  ID user (otomatis dari session jika null)
     */
    public static function log(string $action, string $detail = '', ?int $userId = null): void
    {
        // Ambil user ID dari session jika tidak disediakan
        if ($userId === null) {
            $userId = session()->get('user_id') ?? 0;
        }

        // Ambil IP address dari request
        $ip = \Config\Services::request()->getIPAddress();

        // Format timestamp
        $timestamp = date('Y-m-d H:i:s');

        // Susun baris log
        $logLine = sprintf(
            "[%s] [USER:%d] [%s] [IP:%s] %s",
            $timestamp,
            $userId,
            strtoupper($action),
            $ip,
            $detail
        );

        // Tentukan path file log harian
        $logDir = WRITEPATH . 'logs/';
        $logFile = $logDir . 'activity-' . date('Y-m-d') . '.log';

        // Pastikan direktori ada
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Tulis ke file (append mode)
        file_put_contents($logFile, $logLine . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
