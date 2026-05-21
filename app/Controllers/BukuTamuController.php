<?php

namespace App\Controllers;

use App\Models\BukuTamuModel;

class BukuTamuController extends BaseController
{
    public function index()
    {
        $bukuTamuModel = new BukuTamuModel();
        
        $data = [
            'buku_tamu' => $bukuTamuModel->orderBy('created_at', 'DESC')->paginate(20),
            'pager'     => $bukuTamuModel->pager,
        ];
        
        return view('buku_tamu', $data);
    }
}
