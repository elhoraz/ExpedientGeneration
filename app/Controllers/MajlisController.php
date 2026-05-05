<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class MajlisController extends BaseController
{
    public function index()
    {
        return view('majlis_syura');
    }
}
