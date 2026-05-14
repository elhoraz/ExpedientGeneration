<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KontemplasiModel;

class KontemplasiController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $kontemplasiModel = new KontemplasiModel();
        
        // Ambil jurnal milik agen ini saja
        $data['journals'] = $kontemplasiModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return view('ruang_kontemplasi', $data);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        $rules = [
            'content' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/kontemplasi')->with('error', 'Gagal menyimpan. Konten terlalu pendek.');
        }

        $kontemplasiModel = new KontemplasiModel();
        $kontemplasiModel->insert([
            'user_id'    => $userId,
            'content'    => $this->request->getPost('content'),
            'mood'       => $this->request->getPost('mood') ?? 'Netral',
            'is_private' => $this->request->getPost('is_private') ? true : false,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/kontemplasi')->with('success', 'Jurnal berhasil direkam ke dalam Ruang Kontemplasi.');
    }
}
