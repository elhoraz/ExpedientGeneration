<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailQueueModel extends Model
{
    protected $table         = 'email_queue';
    protected $primaryKey    = 'id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'to_email', 'to_name', 'subject', 'body',
        'status', 'attempts', 'max_attempts',
        'error_message', 'scheduled_at', 'sent_at', 'created_at'
    ];
}
