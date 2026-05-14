<?php
namespace App\Models;
use CodeIgniter\Model;

class MultazamModel extends Model
{
    protected $table = 'multazam_prayers';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'prayer_text', 'status', 'created_at'];
    protected $returnType = 'array';
}
