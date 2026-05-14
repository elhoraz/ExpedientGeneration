<?php

namespace App\Services;

use App\Models\UserModel;

class GamificationService
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userModel = new UserModel();
    }

    /**
     * Menambahkan poin prestise ke pengguna dan mencatat log.
     * Mengembalikan true jika berhasil ditambah, false jika kena limit harian.
     */
    public function addPrestise(int $userId, string $activityName, int $points): bool
    {
        // Pengecekan limitasi (Anti-spam)
        // Contoh: LOGIN_DAILY hanya bisa 1x per hari
        if ($activityName === 'LOGIN_DAILY') {
            $todayLog = $this->db->table('prestise_logs')
                ->where('user_id', $userId)
                ->where('activity_name', $activityName)
                ->where('DATE(created_at)', date('Y-m-d'))
                ->countAllResults();

            if ($todayLog > 0) {
                return false; // Sudah dapat poin login hari ini
            }
        }

        // Contoh: GUESTBOOK_ENTRY hanya bisa 1x selamanya per user
        if ($activityName === 'GUESTBOOK_ENTRY') {
            $logCount = $this->db->table('prestise_logs')
                ->where('user_id', $userId)
                ->where('activity_name', $activityName)
                ->countAllResults();
            
            if ($logCount > 0) {
                return false;
            }
        }

        // Catat ke log
        $this->db->table('prestise_logs')->insert([
            'user_id'       => $userId,
            'activity_name' => $activityName,
            'points'        => $points,
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        // Tambahkan ke total user
        $this->db->query("UPDATE users SET prestise_points = prestise_points + ? WHERE id = ?", [$points, $userId]);

        return true;
    }

    /**
     * Mengembalikan gelar kehormatan berdasarkan jumlah poin prestise.
     */
    public function getGelar(int $points): string
    {
        if ($points >= 1000) return 'Pilar Utama';
        if ($points >= 600)  return 'Visioner';
        if ($points >= 300)  return 'Intelektual';
        if ($points >= 100)  return 'Penggerak';
        return 'Perintis';
    }

    /**
     * Mengembalikan warna badge berdasarkan jumlah poin prestise.
     */
    public function getBadgeColor(int $points): string
    {
        if ($points >= 1000) return 'linear-gradient(135deg, #FFD700 0%, #D4AF37 100%)'; // Gold
        if ($points >= 600)  return 'linear-gradient(135deg, #E5E4E2 0%, #BFC1C2 100%)'; // Platinum
        if ($points >= 300)  return 'linear-gradient(135deg, #b87333 0%, #cd7f32 100%)'; // Bronze
        if ($points >= 100)  return 'linear-gradient(135deg, #4A4A4A 0%, #2A2A2A 100%)'; // Dark Steel
        return 'linear-gradient(135deg, #222 0%, #111 100%)'; // Base
    }
}
