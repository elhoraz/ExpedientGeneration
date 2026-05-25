<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\WhatsappQueueModel;

/**
 * WhatsAppService
 *
 * Mengelola pengiriman notifikasi WhatsApp via Fonnte API.
 * Pesan disimpan ke tabel `whatsapp_queue` dan diproses oleh
 * CLI command `php spark wa:process`.
 *
 * Usage:
 *   $wa = new WhatsAppService();
 *   $wa->enqueue('628123456789', 'Budi', 'Halo!');
 *
 * Docs Fonnte: https://fonnte.com/docs
 */
class WhatsAppService
{
    protected WhatsappQueueModel $model;
    protected UserModel $userModel;
    protected string $token;
    protected string $apiUrl;

    public function __construct()
    {
        $this->model     = new WhatsappQueueModel();
        $this->userModel = new UserModel();
        $this->token     = env('FONNTE_TOKEN', '');
        $this->apiUrl    = env('FONNTE_URL', 'https://api.fonnte.com/send');
    }

    // =========================================================
    // CORE: QUEUE & PROCESS
    // =========================================================

    /**
     * Normalisasi nomor WA ke format internasional (62xxx).
     * Contoh: 081234 → 6281234 | 6281234 → 6281234
     */
    public function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D/', '', $number); // Hapus non-digit
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }
        return $number;
    }

    /**
     * Tambahkan pesan ke antrian WhatsApp.
     */
    public function enqueue(string $number, string $name, string $message, ?string $scheduledAt = null): int
    {
        $normalized = $this->normalizeNumber($number);

        $this->model->insert([
            'to_number'    => $normalized,
            'to_name'      => $name,
            'message'      => $message,
            'status'       => 'pending',
            'attempts'     => 0,
            'max_attempts' => 3,
            'scheduled_at' => $scheduledAt ?? date('Y-m-d H:i:s'),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->model->getInsertID();
    }

    /**
     * Proses antrian WhatsApp: ambil pesan pending, kirim via Fonnte, update status.
     *
     * @param int $limit Jumlah pesan per batch
     * @return array Statistik [sent, failed, skipped]
     */
    public function processQueue(int $limit = 20): array
    {
        $stats = ['sent' => 0, 'failed' => 0, 'skipped' => 0];

        if (empty($this->token)) {
            log_message('warning', '[WhatsAppService] FONNTE_TOKEN belum dikonfigurasi. Pengiriman dilewati.');
            return $stats;
        }

        $messages = $this->model
            ->where('status', 'pending')
            ->where('scheduled_at <=', date('Y-m-d H:i:s'))
            ->where('attempts <', 3)
            ->orderBy('created_at', 'ASC')
            ->limit($limit)
            ->findAll();

        if (empty($messages)) {
            return $stats;
        }

        foreach ($messages as $msg) {
            try {
                $result = $this->sendViaFonnte($msg['to_number'], $msg['message']);

                if ($result['success']) {
                    $this->model->update($msg['id'], [
                        'status'  => 'sent',
                        'sent_at' => date('Y-m-d H:i:s'),
                    ]);
                    $stats['sent']++;
                } else {
                    $this->markFailed($msg, $result['error']);
                    $stats['failed']++;
                }
            } catch (\Exception $e) {
                $this->markFailed($msg, $e->getMessage());
                $stats['failed']++;
            }

            // Jeda 300ms antar pesan agar tidak kena rate-limit Fonnte
            usleep(300000);
        }

        return $stats;
    }

    /**
     * Kirim langsung via Fonnte API (tanpa queue).
     * Digunakan untuk ulang tahun yang harus tepat waktu.
     */
    public function sendDirect(string $number, string $message): array
    {
        if (empty($this->token)) {
            log_message('warning', '[WhatsAppService] FONNTE_TOKEN belum dikonfigurasi.');
            return ['success' => false, 'error' => 'Token tidak dikonfigurasi'];
        }
        return $this->sendViaFonnte($this->normalizeNumber($number), $message);
    }

    // =========================================================
    // HELPER: SHORTCUT NOTIFIKASI
    // =========================================================

    /**
     * Broadcast ke SEMUA alumni yang opt-in.
     * Tambahkan ke antrian — tidak blocking.
     */
    public function broadcastToAll(string $message): int
    {
        $alumni = $this->userModel
            ->where('wa_notif_opt_in', 1)
            ->where('no_whatsapp IS NOT NULL', null, false)
            ->where('no_whatsapp !=', '')
            ->where('email_verified_at IS NOT NULL', null, false)
            ->findAll();

        $count = 0;
        foreach ($alumni as $user) {
            $this->enqueue($user['no_whatsapp'], $user['nama_panggilan'], $message);
            $count++;
        }

        return $count;
    }

    /**
     * Notifikasi: Alumni baru bergabung.
     */
    public function notifyNewAlumni(string $namaAlumniBaru): int
    {
        $baseUrl = rtrim(env('app.baseURL', ''), '/');
        $message = "🌟 *Expedient Generation*\n\n"
                 . "👋 Entitas baru telah bergabung!\n\n"
                 . "*{$namaAlumniBaru}* baru saja masuk ke portal angkatan.\n\n"
                 . "Sambut kedatangannya 🤝\n"
                 . "🔗 {$baseUrl}/direktori";

        return $this->broadcastToAll($message);
    }

    /**
     * Notifikasi: Event/agenda baru dibuat.
     */
    public function notifyNewEvent(string $judulEvent, string $tanggal, string $lokasi = ''): int
    {
        $baseUrl  = rtrim(env('app.baseURL', ''), '/');
        $lokasiBaris = $lokasi ? "\n📍 Lokasi: {$lokasi}" : '';
        $message  = "📅 *Expedient Generation*\n\n"
                  . "✨ *Agenda Baru Dijadwalkan!*\n\n"
                  . "📌 {$judulEvent}\n"
                  . "🗓️ Tanggal: {$tanggal}"
                  . $lokasiBaris . "\n\n"
                  . "Konfirmasi kehadiran Anda:\n"
                  . "🔗 {$baseUrl}/event";

        return $this->broadcastToAll($message);
    }

    /**
     * Notifikasi: Pengumuman admin baru.
     */
    public function notifyAnnouncement(string $judul): int
    {
        $baseUrl = rtrim(env('app.baseURL', ''), '/');
        $message = "📢 *Expedient Generation*\n\n"
                 . "*Pengumuman Terbaru:*\n\n"
                 . "📜 {$judul}\n\n"
                 . "Buka platform untuk membaca selengkapnya:\n"
                 . "🔗 {$baseUrl}/beranda";

        return $this->broadcastToAll($message);
    }

    /**
     * Notifikasi: Ucapan ulang tahun personal.
     * Dikirim langsung (tidak via queue) untuk presisi waktu.
     */
    public function sendBirthdayWish(string $number, string $nama, int $usia): array
    {
        $baseUrl = rtrim(env('app.baseURL', ''), '/');
        $message = "🎂 *Expedient Generation*\n\n"
                 . "Selamat ulang tahun, *{$nama}*! 🎉\n\n"
                 . "Semoga di usia {$usia} ini,\n"
                 . "Allah limpahkan keberkahan, kesehatan,\n"
                 . "dan pencapaian terbaikmu. Aamiin 🤲\n\n"
                 . "_Salam hangat dari seluruh Angkatan 42 Arrisalah_ 🌟\n\n"
                 . "🔗 {$baseUrl}/birthday/{$this->getUserIdByNumber($number)}";

        return $this->sendDirect($number, $message);
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    /**
     * Kirim pesan via Fonnte HTTP API.
     */
    private function sendViaFonnte(string $number, string $message): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $this->token,
            ],
            CURLOPT_POSTFIELDS => [
                'target'  => $number,
                'message' => $message,
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            log_message('error', "[WhatsAppService] cURL error ke {$number}: {$error}");
            return ['success' => false, 'error' => $error];
        }

        $decoded = json_decode($response, true);

        // Fonnte return {"status":true} saat sukses
        if ($httpCode === 200 && isset($decoded['status']) && $decoded['status'] === true) {
            log_message('info', "[WhatsAppService] Terkirim ke {$number}");
            return ['success' => true, 'error' => ''];
        }

        $errMsg = $decoded['reason'] ?? $decoded['message'] ?? "HTTP {$httpCode}";
        log_message('warning', "[WhatsAppService] Gagal ke {$number}: {$errMsg}");
        return ['success' => false, 'error' => $errMsg];
    }

    /**
     * Tandai pesan gagal & increment attempts.
     */
    private function markFailed(array $msg, string $error): void
    {
        $attempts = $msg['attempts'] + 1;
        $status   = $attempts >= $msg['max_attempts'] ? 'failed' : 'pending';

        $this->model->update($msg['id'], [
            'attempts'      => $attempts,
            'status'        => $status,
            'error_message' => substr($error, 0, 1000),
        ]);
    }

    /**
     * Cari user ID berdasarkan nomor WA (untuk link birthday).
     */
    private function getUserIdByNumber(string $number): int
    {
        $normalized = $this->normalizeNumber($number);
        // Coba dengan format lokal juga
        $local = '0' . substr($normalized, 2);

        $user = $this->userModel
            ->groupStart()
                ->where('no_whatsapp', $normalized)
                ->orWhere('no_whatsapp', $local)
            ->groupEnd()
            ->first();

        return $user ? (int)$user['id'] : 0;
    }

    // =========================================================
    // ADMIN: STATISTIK
    // =========================================================

    /**
     * Ambil statistik antrian untuk dashboard admin.
     */
    public function getQueueStats(): array
    {
        $db = \Config\Database::connect();
        return [
            'pending' => $db->table('whatsapp_queue')->where('status', 'pending')->countAllResults(),
            'sent'    => $db->table('whatsapp_queue')->where('status', 'sent')->countAllResults(),
            'failed'  => $db->table('whatsapp_queue')->where('status', 'failed')->countAllResults(),
            'total'   => $db->table('whatsapp_queue')->countAllResults(),
        ];
    }

    /**
     * Ambil log antrian terbaru untuk admin.
     */
    public function getRecentQueue(int $limit = 50): array
    {
        return $this->model
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
