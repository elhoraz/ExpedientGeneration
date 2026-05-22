<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventRsvpModel;
use App\Services\PusherService;
use App\Services\GamificationService;

class EventController extends BaseController
{
    public function index()
    {
        $eventModel = new EventModel();
        $rsvpModel = new EventRsvpModel();
        $userId = session()->get('user_id');

        $events = $eventModel->getEventsWithCreator();

        // Attach RSVP stats & current user's RSVP status
        foreach ($events as &$event) {
            $event['stats'] = $rsvpModel->getRsvpStats($event['id']);
            
            // Check current user's RSVP
            $userRsvp = $rsvpModel->where('event_id', $event['id'])
                                  ->where('user_id', $userId)
                                  ->first();
            $event['my_rsvp'] = $userRsvp ? $userRsvp['status'] : null;
        }

        $data = [
            'title'  => 'Agenda & Eksibisi',
            'events' => $events,
        ];

        return view('event', $data);
    }

    public function store()
    {
        $rules = [
            'title'       => 'required|min_length[5]|max_length[255]',
            'description' => 'required|min_length[10]',
            'event_date'  => 'required|valid_date',
            'location'    => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/event')->withInput()->with('error', 'Gagal membuat agenda. Cek kembali isian Anda.');
        }

        $userId = session()->get('user_id');
        $eventModel = new EventModel();
        
        $eventId = $eventModel->insert([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'event_date'  => $this->request->getPost('event_date'),
            'location'    => $this->request->getPost('location'),
            'created_by'  => $userId,
        ]);

        // Auto-RSVP the creator as 'Hadir'
        $rsvpModel = new EventRsvpModel();
        $rsvpModel->insert([
            'event_id' => $eventId,
            'user_id'  => $userId,
            'status'   => 'Hadir'
        ]);

        // Gamification Reward
        $gamificationService = service('gamificationService');
        $gamificationService->addPrestise($userId, 'CREATE_EVENT', 20);

        // Broadcast Notification
        $pusher = service('pusherService');
        $pusher->broadcastNotification(
            'Agenda Baru',
            $this->request->getPost('title') . ' telah dijadwalkan.',
            '/event'
        );

        return redirect()->to('/event')->with('success', 'Agenda berhasil dipublikasikan.');
    }

    public function rsvp($eventId)
    {
        $status = $this->request->getPost('status');
        $allowedStatuses = ['Hadir', 'Tidak Hadir', 'Tentatif'];

        if (!in_array($status, $allowedStatuses)) {
            return redirect()->to('/event')->with('error', 'Status RSVP tidak valid.');
        }

        $userId = session()->get('user_id');
        $rsvpModel = new EventRsvpModel();

        // Check if RSVP exists
        $existingRsvp = $rsvpModel->where('event_id', $eventId)
                                  ->where('user_id', $userId)
                                  ->first();

        if ($existingRsvp) {
            $rsvpModel->update($existingRsvp['id'], ['status' => $status]);
        } else {
            $rsvpModel->insert([
                'event_id' => $eventId,
                'user_id'  => $userId,
                'status'   => $status
            ]);
            
            // Gamification Reward for first RSVP
            $gamificationService = service('gamificationService');
            $gamificationService->addPrestise($userId, 'RSVP_EVENT', 5);
        }

        return redirect()->to('/event')->with('success', "Status kehadiran Anda diperbarui menjadi: {$status}");
    }
}
