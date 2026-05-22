<?php

namespace App\Services;

use App\Models\UserModel;
use CodeIgniter\Config\Services;

class RadarService
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Mengambil semua node (koordinat alumni) untuk ditampilkan di peta
     */
    public function getMapNodes(): array
    {
        // Gunakan cache agar query tidak berat
        $cache = Services::cache();
        $cacheKey = 'radar_nodes';
        
        if ($cached = $cache->get($cacheKey)) {
            return $cached;
        }

        // Ambil data alumni yang memiliki koordinat
        $users = $this->userModel
            ->select('id, nama_lengkap, nama_panggilan, jenis_kelamin, tempat_lahir, alamat_lengkap, lat, lng, foto_profil, no_whatsapp')
            ->where('lat IS NOT NULL')
            ->where('lng IS NOT NULL')
            ->findAll();

        $nodes = [];
        
        // Asumsikan Pondok Modern Arrisalah sebagai Center Node
        $nodes[] = [
            'id'    => 'center',
            'name'  => 'Pondok Modern Arrisalah',
            'nick'  => 'Arrisalah',
            'city'  => 'Ponorogo, Jawa Timur',
            'lat'   => -8.0358875,
            'lng'   => 111.4145280,
            'type'  => 'center',
            'foto'  => null,
            'wa'    => null,
            'gender'=> null,
        ];

        foreach ($users as $user) {
            // Ekstrak kota dari alamat jika memungkinkan, atau gunakan kota kelahiran
            $city = "Lokasi Tidak Diketahui";
            if (!empty($user['alamat_lengkap'])) {
                $city = $user['alamat_lengkap']; 
            } else if (!empty($user['tempat_lahir'])) {
                $city = $user['tempat_lahir'];
            }

            $nodes[] = [
                'id'     => $user['id'],
                'name'   => $user['nama_lengkap'],
                'nick'   => $user['nama_panggilan'] ?? '',
                'city'   => $city,
                'lat'    => (float) $user['lat'],
                'lng'    => (float) $user['lng'],
                'type'   => 'agent',
                'foto'   => $user['foto_profil'] ?? null,
                'wa'     => $user['no_whatsapp'] ?? null,
                'gender' => $user['jenis_kelamin'] ?? null,
            ];
        }

        // Simpan ke cache selama 5 menit
        $cache->save($cacheKey, $nodes, 300);

        return $nodes;
    }

    /**
     * Memperbarui lokasi user
     */
    public function updateLocation(int $userId, float $lat, float $lng, string $alamat = null): bool
    {
        $data = [
            'lat' => $lat,
            'lng' => $lng
        ];

        if ($alamat) {
            $data['alamat_lengkap'] = $alamat;
        }

        $result = $this->userModel->update($userId, $data);
        
        if ($result) {
            // Invalidate cache
            Services::cache()->delete('radar_nodes');
            
            // Log activity
            \App\Libraries\ActivityLogger::log('LOCATION_UPDATE', 'Koordinat GPS diperbarui: ' . $lat . ', ' . $lng, $userId);
            
            return true;
        }
        
        return false;
    }
}
