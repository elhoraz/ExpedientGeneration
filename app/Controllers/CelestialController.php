<?php

namespace App\Controllers;

use App\Models\CelestialModel;

class CelestialController extends BaseController
{
    public function index()
    {
        $model = new CelestialModel();
        
        // Ambil semua kartu dari database
        $data['cards'] = $model->findAll();
        
        return view('celestial_codex', $data);
    }
}
