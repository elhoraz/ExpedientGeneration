<?php

namespace App\Models;

use CodeIgniter\Model;

class WhatsappQueueModel extends Model
{
    protected $table      = 'whatsapp_queue';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'to_number',
        'to_name',
        'message',
        'status',
        'attempts',
        'max_attempts',
        'error_message',
        'scheduled_at',
        'sent_at',
        'created_at',
    ];

    protected $useTimestamps = false;
}
