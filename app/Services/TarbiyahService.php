<?php

namespace App\Services;

use App\Models\TarbiyahMentorModel;
use App\Models\TarbiyahTenderModel;
use App\Models\TarbiyahRequestModel;

class TarbiyahService
{
    /**
     * Mengambil data dashboard Tarbiyah Nexus
     */
    public function getDashboardData()
    {
        $mentorModel = new TarbiyahMentorModel();
        $tenderModel = new TarbiyahTenderModel();

        return [
            'mentors' => $mentorModel->findAll(),
            'tenders' => $tenderModel->findAll()
        ];
    }

    /**
     * Menyimpan permohonan mentor atau tender
     */
    public function submitRequest($userId, $targetId, $type)
    {
        $model = new TarbiyahRequestModel();
        
        $model->insert([
            'user_id'    => $userId,
            'target_id'  => $targetId,
            'type'       => $type,
            'status'     => 'Pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return true;
    }
}
