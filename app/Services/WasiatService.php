<?php

namespace App\Services;

use App\Models\WasiatModel;

/**
 * WasiatService
 * 
 * Menangani logika bisnis untuk Wasiat Vault (Enkripsi, Dekripsi, dan Penyimpanan).
 */
class WasiatService
{
    protected WasiatModel $wasiatModel;

    public function __construct()
    {
        $this->wasiatModel = new WasiatModel();
    }

    /**
     * Mengenkripsi pesan dan menyimpannya ke database.
     */
    public function encryptAndStore(int $userId, string $message, string $passphrase): void
    {
        $method = 'aes-256-cbc';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        $encrypted = openssl_encrypt($message, $method, $passphrase, 0, $iv);
        $encryptedPayload = base64_encode($iv . $encrypted);

        $this->wasiatModel->insert([
            'sender_id'         => $userId,
            'passphrase_hash'   => password_hash($passphrase, PASSWORD_DEFAULT),
            'encrypted_message' => $encryptedPayload,
            'created_at'        => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mencoba mendekripsi pesan. Mengembalikan teks asli jika berhasil, null jika gagal.
     * @throws \Exception Jika dokumen tidak ditemukan.
     */
    public function unlock(int $wasiatId, string $passphrase): ?string
    {
        $wasiat = $this->wasiatModel->find($wasiatId);
        if (!$wasiat) {
            throw new \Exception('Dokumen wasiat tidak ditemukan.');
        }

        if (password_verify($passphrase, $wasiat['passphrase_hash'])) {
            $method = 'aes-256-cbc';
            $payload = base64_decode($wasiat['encrypted_message']);
            $ivLength = openssl_cipher_iv_length($method);
            $iv = substr($payload, 0, $ivLength);
            $encrypted = substr($payload, $ivLength);
            
            $decrypted = openssl_decrypt($encrypted, $method, $passphrase, 0, $iv);

            if ($decrypted !== false) {
                return $decrypted;
            }
        }

        return null; // Passphrase salah atau dekripsi gagal
    }
}
