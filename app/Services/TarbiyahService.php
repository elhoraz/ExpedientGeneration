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
    public function getDashboardData($userId)
    {
        $mentorModel = new TarbiyahMentorModel();
        $tenderModel = new TarbiyahTenderModel();
        $requestModel = new TarbiyahRequestModel();

        // Get user requests with related target names
        $db = \Config\Database::connect();
        
        $mentorRequests = $db->table('tarbiyah_requests')
            ->select('tarbiyah_requests.*, tarbiyah_mentors.name as target_name')
            ->join('tarbiyah_mentors', 'tarbiyah_mentors.id = tarbiyah_requests.target_id')
            ->where('tarbiyah_requests.user_id', $userId)
            ->where('tarbiyah_requests.type', 'Mentor')
            ->get()->getResultArray();

        $tenderRequests = $db->table('tarbiyah_requests')
            ->select('tarbiyah_requests.*, tarbiyah_tenders.title as target_name')
            ->join('tarbiyah_tenders', 'tarbiyah_tenders.id = tarbiyah_requests.target_id')
            ->where('tarbiyah_requests.user_id', $userId)
            ->where('tarbiyah_requests.type', 'Tender')
            ->get()->getResultArray();

        $myRequests = array_merge($mentorRequests, $tenderRequests);

        // Sort by created_at desc
        usort($myRequests, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return [
            'mentors' => $mentorModel->findAll(),
            'tenders' => $tenderModel->findAll(),
            'my_requests' => $myRequests
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
