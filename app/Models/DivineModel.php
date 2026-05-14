<?php

namespace App\Models;

use CodeIgniter\Model;

class DivineModel extends Model
{
    protected $table = 'divine_verses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['arabic', 'latin', 'meaning', 'source'];
}
