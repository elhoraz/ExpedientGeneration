<?php

namespace App\Controllers;

use App\Services\RadarService;

class RadarController extends BaseController
{
    protected RadarService $radarService;

    public function __construct()
    {
        $this->radarService = service('radarService');
    }

    public function index()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        return view('radar', $data);
    }

    public function flatMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'minimalist';
        return view('radar_2d', $data);
    }

    public function satelliteMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'satellite';
        return view('radar_2d', $data);
    }

    public function terrainMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'terrain';
        return view('radar_2d', $data);
    }

    public function darkMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'dark';
        return view('radar_2d', $data);
    }

    public function watercolorMap() // Route is watercolor, but it renders Google Maps
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'google';
        return view('radar_2d', $data);
    }

    public function classicMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'classic';
        return view('radar_2d', $data);
    }

    public function natgeoMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'natgeo';
        return view('radar_2d', $data);
    }

    public function voyagerMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'voyager';
        return view('radar_2d', $data);
    }

    public function hybridMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'hybrid';
        return view('radar_2d', $data);
    }

    public function graycanvasMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'graycanvas';
        return view('radar_2d', $data);
    }

    public function hotMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'hot';
        return view('radar_2d', $data);
    }

    public function googleterrainMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'googleterrain';
        return view('radar_2d', $data);
    }

    public function esriclarityMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'esriclarity';
        return view('radar_2d', $data);
    }

    public function nightnavMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'nightnav';
        return view('radar_2d', $data);
    }

    public function googletransitMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'googletransit';
        return view('radar_2d', $data);
    }

    public function physicalMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'physical';
        return view('radar_2d', $data);
    }

    public function nasamarbleMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'nasamarble';
        return view('radar_2d', $data);
    }

    public function googletrafficMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'googletraffic';
        return view('radar_2d', $data);
    }

    public function navigationMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'navigation';
        return view('radar_2d', $data);
    }

    public function esristreetMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'esristreet';
        return view('radar_2d', $data);
    }

    public function tonerMap()
    {
        $data['alumni_nodes'] = $this->radarService->getMapNodes();
        $data['mapStyle'] = 'toner';
        return view('radar_2d', $data);
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
