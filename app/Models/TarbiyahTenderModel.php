<?php

namespace App\Models;

use CodeIgniter\Model;

class TarbiyahTenderModel extends Model
{
    protected $table = 'tarbiyah_tenders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'company', 'description', 'classification'];
}
