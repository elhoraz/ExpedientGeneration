<?php

namespace App\Services;

use Pusher\Pusher;

/**
 * PusherService
 * 
 * Singleton-style service untuk mengelola koneksi Pusher.
 * Menggantikan instansiasi manual `new Pusher(...)` yang tersebar
 * di AuthController, ChatController, dan tempat lainnya.
 * 
 * Usage:
 *   $pusherService = new PusherService();
 *   $pusherService->trigger('channel-name', 'event-name', ['key' => 'value']);
 */
class PusherService
{
    protected ?Pusher $pusher = null;

    /**
     * Mendapatkan instance Pusher (lazy-loaded).
     * Mendukung Pusher Cloud dan Soketi self-hosted.
     */
    protected function getPusher(): Pusher
    {
        if ($this->pusher === null) {
            $options = [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap1'),
                'useTLS'  => env('PUSHER_USE_TLS', 'true') === 'true',
            ];

            // Soketi self-hosted: tambahkan host/port/scheme
            $host = env('PUSHER_HOST');
            if (!empty($host)) {
                $options['host']   = $host;
                $options['port']   = (int) env('PUSHER_PORT', 6001);
                $options['scheme'] = env('PUSHER_SCHEME', 'https');
            }

            $this->pusher = new Pusher(
                env('PUSHER_APP_KEY', 'app-key'),
                env('PUSHER_APP_SECRET', 'app-secret'),
                env('PUSHER_APP_ID', 'app-id'),
                $options
            );
        }

        return $this->pusher;
    }

    /**
     * Mengirim event ke channel Pusher.
     * 
     * @param string $channel Nama channel
     * @param string $event   Nama event
     * @param array  $data    Data payload
     * @return bool True jika berhasil, false jika gagal
     */
    public function trigger(string $channel, string $event, array $data): bool
    {
        try {
            $this->getPusher()->trigger($channel, $event, $data);
            return true;
        } catch (\Exception $e) {
            log_message('warning', "Pusher gagal mengirim [{$event}] ke [{$channel}]: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Shortcut: Kirim notifikasi alumni baru bergabung.
     */
    public function notifyNewAlumni(string $nama): bool
    {
        return $this->trigger('expedient-channel', 'alumni-baru', [
            'nama'  => $nama,
            'pesan' => 'Baru saja bergabung ke portal angkatan!'
        ]);
    }

    /**
     * Shortcut: Kirim pesan chat real-time.
     */
    public function sendChatMessage(array $messageData): bool
    {
        return $this->trigger('chat-channel', 'new-message', $messageData);
    }

    /**
     * Shortcut: Kirim notifikasi umum ke user tertentu.
     */
    public function sendNotification(int $userId, string $title, string $message, string $link = '#'): bool
    {
        return $this->trigger('expedient-channel', 'new-notification', [
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }
}
