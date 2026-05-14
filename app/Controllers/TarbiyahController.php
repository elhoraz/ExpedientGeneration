<?php

namespace App\Controllers;

use App\Models\TarbiyahMentorModel;
use App\Models\TarbiyahTenderModel;
use App\Models\TarbiyahRequestModel;

class TarbiyahController extends BaseController
{
    public function index()
    {
        $service = new \App\Services\TarbiyahService();
        $data = $service->getDashboardData();

        return view('tarbiyah_nexus', $data);
    }

    public function request()
    {
        $service = new \App\Services\TarbiyahService();
        
        // ================= VALIDASI INPUT =================
        $rules = [
            'target_id' => 'required|numeric',
            'type'      => 'required|in_list[Mentor,Tender]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Permohonan tidak valid. Pastikan data terisi dengan benar.');
        }

        $targetId = $this->request->getPost('target_id');
        $type     = $this->request->getPost('type');
        $userId   = session()->get('user_id');

        $service->submitRequest($userId, $targetId, $type);

        return redirect()->back()->with('success', 'Permohonan ' . $type . ' telah dikirim dan disegel.');
    }
}
