<?php

namespace App\Controllers;

use App\Services\RadarService;

class RadarController extends BaseController
{
    protected RadarService $radarService;

    public function __construct()
    {
        $this->radarService = new RadarService();
    }

    public function index()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar', $data);
    }

    public function flatMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar_flat', $data);
    }

    public function satelliteMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar_satellite', $data);
    }

    public function terrainMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar_terrain', $data);
    }

    public function darkMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar_dark', $data);
    }

    public function watercolorMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar_watercolor', $data);
    }

    public function classicMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar_classic', $data);
    }

    public function updateLocation()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthenticated']);
        }

        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');
        $city = $this->request->getPost('city');

        if (!is_numeric($lat) || !is_numeric($lng)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Koordinat tidak valid']);
        }

        $success = $this->radarService->updateLocation($userId, (float) $lat, (float) $lng, $city);

        if ($success) {
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => 'Domisili berhasil diselaraskan',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Gagal menyimpan domisili',
            'csrf_hash' => csrf_hash()
        ]);
    }
}
