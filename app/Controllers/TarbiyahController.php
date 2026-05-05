<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TarbiyahController extends BaseController
{
    public function index()
    {
        return view('tarbiyah_nexus');
    }
}
