<?php

namespace App\Models;

use CodeIgniter\Model;

class TarbiyahMentorModel extends Model
{
    protected $table = 'tarbiyah_mentors';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'role', 'description', 'avatar_url', 'slots'];
}
