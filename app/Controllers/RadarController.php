<?php

namespace App\Controllers;

use App\Models\UserModel;
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
        $data['alumni_nodes'] = $this->radarService->getRadarNodes();
        // Kirim email Nominatim dari .env agar tidak hardcoded di view
        $data['nominatim_email'] = env('NOMINATIM_EMAIL', 'admin@expedient.com');

        return view('radar', $data);
    }

    public function flatMap()
    {
        $data['alumni_nodes'] = $this->radarService->getRadarNodes();
        return view('radar_flat', $data);
    }

}