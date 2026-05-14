<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Libraries\ActivityLogger;

/**
 * LocationApi
 * 
 * REST API endpoint untuk operasi geolocation.
 * Menggantikan RadarController::updateLocation() dengan format response standar.
 */
class LocationApi extends BaseApiController
{
    public function update()
    {
        $json = $this->request->getJSON();
        $lat = $json->lat ?? null;
        $lng = $json->lng ?? null;

        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->jsonError('Sesi login tidak ditemukan.', 401);
        }

        if (!is_numeric($lat) || !is_numeric($lng)) {
            return $this->jsonError('Koordinat tidak valid. Latitude dan longitude harus berupa angka.', 422);
        }

        // Validasi range koordinat
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return $this->jsonError('Koordinat di luar jangkauan valid.', 422);
        }

        $userModel = new UserModel();
        $userModel->update($userId, [
            'lat' => (float)$lat,
            'lng' => (float)$lng
        ]);

        // Invalidate cache
        \Config\Services::cache()->delete('radar_nodes');

        ActivityLogger::log('LOCATION_UPDATE', "Koordinat: {$lat}, {$lng}", $userId);

        return $this->jsonSuccess(
            ['lat' => (float)$lat, 'lng' => (float)$lng],
            'Koordinat berhasil diperbarui.'
        );
    }
}
