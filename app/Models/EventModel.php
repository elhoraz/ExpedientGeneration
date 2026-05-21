<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'event_date', 'location', 'created_by'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get events with creator info
     */
    public function getEventsWithCreator()
    {
        return $this->select('events.*, users.nama_panggilan as creator_name, users.foto_profil')
                    ->join('users', 'users.id = events.created_by')
                    ->orderBy('events.event_date', 'ASC')
                    ->findAll();
    }
}
