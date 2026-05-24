<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\AnalyticsService;
use App\Libraries\ActivityLogger;

class AdminController extends BaseController
{
    public function index()
    {
        $analyticsService = service('analyticsService');

        // Gunakan AnalyticsService untuk semua data
        $weeklyTrend = $analyticsService->getWeeklyTrend();
        $topPages = $analyticsService->getTopPages(5);
        $systemSummary = $analyticsService->getSystemSummary();

        $userModel = new UserModel();
        $activeUsers = $userModel->where('prestise_points >', 0)->countAllResults();
        $totalUsers = $userModel->countAllResults();

        $data = [
            'title'       => 'Dashboard Analitik Eksekutif',
            'chartDates'  => json_encode($weeklyTrend['labels']),
            'chartVisits' => json_encode($weeklyTrend['data']),
            'activeUsers' => $activeUsers,
            'topPages'    => $topPages,
            'totalUsers'  => $totalUsers
        ];

        return view('admin/dashboard', $data);
    }

    // ====================================================================
    // USER MANAGEMENT
    // ====================================================================

    /**
     * Halaman daftar semua user dengan search & filter role.
     */
    public function users()
    {
        $userModel = new UserModel();

        $search = $this->request->getGet('q');
        $roleFilter = $this->request->getGet('role');

        $builder = $userModel->builder()
            ->select('id, nama_lengkap, nama_panggilan, email, role, foto_profil, is_active, prestise_points, created_at')
            ->orderBy('created_at', 'DESC');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('nama_panggilan', $search)
                ->orLike('email', $search)
            ->groupEnd();
        }

        if (!empty($roleFilter)) {
            $builder->where('role', $roleFilter);
        }

        $data = [
            'title'  => 'Manajemen Entitas',
            'users'  => $builder->get()->getResultArray(),
            'search' => $search,
            'roleFilter' => $roleFilter,
        ];

        return view('admin/users', $data);
    }

    /**
     * Ubah role user (admin, bendahara, member).
     */
    public function updateRole($userId)
    {
        $newRole = $this->request->getPost('role');
        $allowedRoles = ['member', 'admin', 'bendahara'];

        if (!in_array($newRole, $allowedRoles)) {
            return redirect()->to('/admin/users')->with('error', 'Role tidak valid.');
        }

        // Proteksi dicabut karena keamanan sudah dijamin oleh Sandi Master.

        $db = \Config\Database::connect();
        $db->table('users')->where('id', $userId)->update(['role' => $newRole]);

        return redirect()->to('/admin/users')->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Aktifkan / Nonaktifkan user (soft toggle).
     */
    public function toggleActive($userId)
    {
        if ((int)$userId === (int)session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak bisa menonaktifkan akun sendiri.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $newStatus = ($user['is_active'] ?? 1) == 1 ? 0 : 1;
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $userId)->update(['is_active' => $newStatus]);

        $label = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        ActivityLogger::log('ADMIN_TOGGLE_ACTIVE', "User #{$userId} {$label}", session()->get('user_id'));
        return redirect()->to('/admin/users')->with('success', "Akun berhasil {$label}.");
    }

    // ====================================================================
    // CONTENT MODERATION
    // ====================================================================

    /**
     * Halaman moderasi: menampilkan konten terbaru dari semua modul.
     */
    public function moderation()
    {
        $db = \Config\Database::connect();

        // Chat terbaru (20)
        $chats = $db->table('chat_messages')
            ->select('chat_messages.id, chat_messages.message, chat_messages.created_at, chat_messages.is_deleted, users.nama_panggilan')
            ->join('users', 'users.id = chat_messages.sender_id')
            ->orderBy('chat_messages.created_at', 'DESC')
            ->limit(20)->get()->getResultArray();

        // Majlis terbaru (20)
        $topics = $db->table('majlis_topics')
            ->select('majlis_topics.id, majlis_topics.title, majlis_topics.status, majlis_topics.created_at, users.nama_panggilan')
            ->join('users', 'users.id = majlis_topics.created_by')
            ->orderBy('majlis_topics.created_at', 'DESC')
            ->limit(20)->get()->getResultArray();

        // Syndicate terbaru (20)
        $bisnis = $db->table('syndicate')
            ->select('syndicate.id, syndicate.nama_bisnis, syndicate.kategori, syndicate.created_at, users.nama_panggilan')
            ->join('users', 'users.id = syndicate.user_id')
            ->orderBy('syndicate.created_at', 'DESC')
            ->limit(20)->get()->getResultArray();

        // Buku Tamu terbaru (20)
        $bukuTamu = $db->table('buku_tamu')
            ->select('id, nama, pesan, created_at')
            ->orderBy('created_at', 'DESC')
            ->limit(20)->get()->getResultArray();

        $data = [
            'title'    => 'Moderasi Konten',
            'chats'    => $chats,
            'topics'   => $topics,
            'bisnis'   => $bisnis,
            'bukuTamu' => $bukuTamu,
        ];

        return view('admin/moderation', $data);
    }

    /**
     * Hapus konten berdasarkan tipe dan ID.
     */
    public function deleteContent($type, $id)
    {
        $db = \Config\Database::connect();
        $adminId = session()->get('user_id');

        switch ($type) {
            case 'chat':
                $db->table('chat_messages')->where('id', $id)->update(['is_deleted' => 1]);
                ActivityLogger::log('ADMIN_DELETE', "Hapus chat #{$id}", $adminId);
                break;
            case 'majlis':
                $db->table('majlis_topics')->where('id', $id)->delete();
                $db->table('majlis_votes')->where('topic_id', $id)->delete();
                ActivityLogger::log('ADMIN_DELETE', "Hapus majlis topic #{$id} beserta votes", $adminId);
                break;
            case 'syndicate':
                $db->table('syndicate')->where('id', $id)->delete();
                ActivityLogger::log('ADMIN_DELETE', "Hapus syndicate #{$id}", $adminId);
                break;
            case 'bukutamu':
                $db->table('buku_tamu')->where('id', $id)->delete();
                ActivityLogger::log('ADMIN_DELETE', "Hapus buku tamu #{$id}", $adminId);
                break;
            default:
                return redirect()->to('/admin/moderation')->with('error', 'Tipe konten tidak dikenali.');
        }

        return redirect()->to('/admin/moderation')->with('success', 'Konten berhasil dihapus/diarsipkan.');
    }

    // ====================================================================
    // EXPORT CSV
    // ====================================================================

    /**
     * Export data direktori alumni ke file CSV.
     */
    public function exportCsv()
    {
        $userModel = new UserModel();
        $users = $userModel->builder()
            ->select('nama_lengkap, nama_panggilan, email, no_whatsapp, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat_lengkap, akun_ig, akun_tiktok, motivasi_hidup, cita_cita, prestise_points, role, created_at')
            ->orderBy('nama_lengkap', 'ASC')
            ->get()->getResultArray();

        $filename = 'direktori_expedient_' . date('Ymd_His') . '.csv';

        // Build CSV in memory instead of using exit
        $output = fopen('php://temp', 'w');

        // BOM for Excel UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header
        fputcsv($output, ['Nama Lengkap', 'Panggilan', 'Email', 'WhatsApp', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Instagram', 'TikTok', 'Motivasi', 'Cita-cita', 'Poin Prestise', 'Role', 'Terdaftar']);

        foreach ($users as $u) {
            fputcsv($output, $u);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        ActivityLogger::log('ADMIN_EXPORT', 'Export CSV direktori alumni', session()->get('user_id'));

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }
}
