<?php

namespace App\Controllers;

use App\Models\DivineModel;

class DivineController extends BaseController
{
    public function index()
    {
        $model = new DivineModel();
        
        // Ambil semua ayat dari database
        $data['verses'] = $model->findAll();
        
        return view('divine_verse', $data);
    }
}
