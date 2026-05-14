<?php

namespace App\Models;

use CodeIgniter\Model;

class GenesisLogModel extends Model
{
    protected $table = 'genesis_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'event', 'created_at'];
}
