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
     * Mendapatkan instance Pusher Cloud (lazy-loaded).
     * Kredensial dibaca dari file .env
     */
    protected function getPusher(): Pusher
    {
        if ($this->pusher === null) {
            $this->pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER', 'ap1'),
                    'useTLS'  => true,
                ]
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

    public function broadcastNotification(string $title, string $message, string $link = '#'): bool
    {
        return $this->trigger('expedient-channel', 'broadcast-notification', [
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }

    /**
     * Autentikasi untuk Presence Channel.
     */
    public function presenceAuth(string $channelName, string $socketId, string $userId, array $userInfo = [])
    {
        return $this->getPusher()->presence_auth($channelName, $socketId, $userId, $userInfo);
    }
}
