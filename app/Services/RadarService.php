<?php

namespace App\Services;

use App\Models\UserModel;

/**
 * RadarService
 * 
 * Mengelola logika pengambilan data koordinat geolokasi alumni 
 * untuk keperluan rendering pada peta (Radar).
 */
class RadarService
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Mendapatkan daftar titik koordinat (Pusat & Alumni) dengan Caching.
     */
    public function getRadarNodes(): array
    {
        $pusat = [
            'id'   => 'pusat', 
            'name' => 'Pondok Modern Arrisalah Program Internasional', 
            'city' => 'Slahung, Ponorogo', 
            'lat'  => -8.0358875, 
            'lng'  => 111.4145280, 
            'type' => 'center'
        ];

        $cache = \Config\Services::cache();
        $alumniNodes = $cache->get('radar_nodes');

        if ($alumniNodes === null) {
            $dbUsers = $this->userModel
                            ->where('lat !=', null)
                            ->where('lng !=', null)
                            ->findAll();
            
            $alumniNodes = [$pusat];

            foreach ($dbUsers as $row) {
                $alumniNodes[] = [
                    'id'   => $row['id'],
                    'name' => $row['nama_panggilan'] ?? $row['nama_lengkap'] ?? 'Alumni',
                    'city' => $row['alamat_lengkap'] ?? 'Terdeteksi Radar',
                    'lat'  => (float)$row['lat'],
                    'lng'  => (float)$row['lng'],
                    'type' => 'agent'
                ];
            }

            // Simpan ke cache selama 5 menit
            $cache->save('radar_nodes', $alumniNodes, 300);
        }

        return $alumniNodes;
    }
}
