<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MultazamModel;

class MultazamController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $multazamModel = new MultazamModel();
        
        $data['prayers'] = $multazamModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return view('protokol_multazam', $data);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        $rules = [
            'prayer_text' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/multazam')->with('error', 'Gagal memanjatkan doa. Teks terlalu pendek.');
        }

        $multazamModel = new MultazamModel();
        $multazamModel->insert([
            'user_id'     => $userId,
            'prayer_text' => $this->request->getPost('prayer_text'),
            'status'      => 'Dipanjatkan',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/multazam')->with('success', 'Doa Anda telah dipanjatkan dan direkam dalam Protokol Multazam.');
    }
}
