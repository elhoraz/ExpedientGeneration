<?php

namespace App\Services;

use App\Models\UserModel;
use App\Libraries\ActivityLogger;

/**
 * ProfileService
 * 
 * Memisahkan business logic manajemen profil dari ProfileController.
 * Menangani pemrosesan foto (validasi, resize, cleanup) dan update data.
 * 
 * Usage:
 *   $profileService = new ProfileService();
 *   $filename = $profileService->processProfilePhoto($base64, $userId);
 */
class ProfileService
{
    protected UserModel $userModel;

    /** Tipe MIME yang diizinkan untuk foto profil */
    protected array $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    
    /** Mapping MIME ke ekstensi file */
    protected array $mimeExtensions = [
        'image/jpeg' => '.jpg',
        'image/png'  => '.png',
        'image/webp' => '.webp'
    ];

    /** Ukuran maksimum file dalam bytes (2MB) */
    protected int $maxFileSize = 2 * 1024 * 1024;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Proses foto profil dari Base64 string.
     * Validasi tipe, ukuran, hapus file lama, simpan file baru.
     * 
     * @param string $base64 Data Base64 dari form (format: data:image/jpeg;base64,...)
     * @param int $userId ID user untuk menghapus foto lama
     * @return string|null Nama file baru, atau null jika gagal
     * @throws \InvalidArgumentException Jika format/tipe/ukuran tidak valid
     */
    public function processProfilePhoto(string $base64, int $userId): ?string
    {
        $imageParts = explode(";base64,", $base64);
        if (count($imageParts) !== 2) {
            throw new \InvalidArgumentException('Format Base64 tidak valid.');
        }

        // Validasi MIME type
        $mimeType = str_replace('data:', '', $imageParts[0]);
        if (!in_array($mimeType, $this->allowedMimes)) {
            throw new \InvalidArgumentException('Format foto tidak didukung. Gunakan JPG, PNG, atau WebP.');
        }

        // Decode Base64
        $imageData = base64_decode($imageParts[1]);
        if ($imageData === false) {
            throw new \InvalidArgumentException('Data gambar tidak dapat di-decode.');
        }

        // Validasi ukuran
        if (strlen($imageData) > $this->maxFileSize) {
            throw new \InvalidArgumentException('Ukuran foto terlalu besar. Maksimal 2MB.');
        }

        // VALIDASI KONTEN GAMBAR SEBENARNYA & RESIZE (GD Library)
        $imgInfo = @getimagesizefromstring($imageData);
        if ($imgInfo === false) {
            throw new \InvalidArgumentException('File yang diunggah bukan gambar yang valid.');
        }

        $sourceImage = @imagecreatefromstring($imageData);
        if ($sourceImage === false) {
            throw new \InvalidArgumentException('Gagal memproses gambar. Pastikan format gambar valid.');
        }

        // Resize ke max 512x512
        $maxWidth = 512;
        $maxHeight = 512;
        $width = $imgInfo[0];
        $height = $imgInfo[1];

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)round($width * $ratio);
            $newHeight = (int)round($height * $ratio);
            
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Handle transparency for PNG/WebP
            if ($imgInfo[2] == IMAGETYPE_PNG || $imgInfo[2] == IMAGETYPE_WEBP) {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($sourceImage);
            $sourceImage = $resizedImage;
        }

        // Hapus foto lama (orphaned files cleanup)
        $this->deleteOldPhoto($userId);

        // Simpan file baru sebagai WebP untuk efisiensi
        $filename = uniqid('expedient_') . '.webp';
        $path = FCPATH . 'uploads/profiles/' . $filename;

        // Save and compress (quality 80)
        imagewebp($sourceImage, $path, 80);
        imagedestroy($sourceImage);

        ActivityLogger::log('PHOTO_UPLOAD', "Foto baru: {$filename}", $userId);

        return $filename;
    }

    /**
     * Hapus foto profil lama dari disk.
     * 
     * @param int $userId
     */
    protected function deleteOldPhoto(int $userId): void
    {
        try {
            $user = $this->userModel->find($userId);
            if (!empty($user['foto_profil'])) {
                $oldPath = FCPATH . 'uploads/profiles/' . $user['foto_profil'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                    ActivityLogger::log('PHOTO_CLEANUP', "File lama dihapus: {$user['foto_profil']}", $userId);
                }
            }
        } catch (\Exception $e) {
            log_message('warning', 'Gagal menghapus foto lama: ' . $e->getMessage());
        }
    }

    /**
     * Update data profil user.
     * 
     * @param int $userId
     * @param array $data Data yang akan di-update
     * @return bool
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $result = $this->userModel->update($userId, $data);

        // Invalidate cache
        \Config\Services::cache()->delete('alumni_list');
        \Config\Services::cache()->delete('radar_nodes');

        ActivityLogger::log('PROFILE_UPDATE', "Profil diperbarui: " . ($data['nama_lengkap'] ?? 'unknown'), $userId);

        return $result;
    }
}
