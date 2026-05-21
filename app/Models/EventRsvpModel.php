<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRsvpModel extends Model
{
    protected $table            = 'event_rsvps';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['event_id', 'user_id', 'status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get RSVP stats for a specific event
     */
    public function getRsvpStats($eventId)
    {
        $rsvps = $this->where('event_id', $eventId)->findAll();
        
        $stats = [
            'Hadir' => 0,
            'Tidak Hadir' => 0,
            'Tentatif' => 0,
            'total' => count($rsvps),
            'users' => []
        ];

        if (empty($rsvps)) return $stats;

        // Get user details for these RSVPs
        $db = \Config\Database::connect();
        $builder = $db->table('event_rsvps');
        $builder->select('event_rsvps.status, users.nama_panggilan, users.foto_profil');
        $builder->join('users', 'users.id = event_rsvps.user_id');
        $builder->where('event_rsvps.event_id', $eventId);
        $userRsvps = $builder->get()->getResultArray();

        foreach ($userRsvps as $rsvp) {
            $stats[$rsvp['status']]++;
            $stats['users'][] = $rsvp;
        }

        return $stats;
    }
}
