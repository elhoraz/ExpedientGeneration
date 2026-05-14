<?php

namespace App\Controllers;

class GaleriController extends BaseController
{
    public function index()
    {
        $galeriService = new \App\Services\GaleriService();
        $data['memories'] = $galeriService->getGaleriMemories();

        return view('galeri', $data);
    }
}