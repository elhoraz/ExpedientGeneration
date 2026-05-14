<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function getUnread()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);

        $notifications = $this->notificationModel
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'DESC')
            ->findAll(10); // Limit 10 for dropdown

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    public function markAsRead($id)
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['status' => 'error']);

        $notif = $this->notificationModel->find($id);
        if ($notif && $notif['user_id'] == $userId) {
            $this->notificationModel->update($id, ['is_read' => 1]);
            return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
        }

        return $this->response->setJSON(['status' => 'error', 'csrf_hash' => csrf_hash()]);
    }
}
