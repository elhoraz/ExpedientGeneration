<?php

namespace App\Models;

use CodeIgniter\Model;

class TarbiyahRequestModel extends Model
{
    protected $table = 'tarbiyah_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'target_id', 'type', 'status', 'created_at'];
}
