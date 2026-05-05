<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class KontemplasiController extends BaseController
{
    public function index()
    {
        return view('ruang_kontemplasi');
    }
}
