<?php
namespace App\Models;
use CodeIgniter\Model;

class KontemplasiModel extends Model
{
    protected $table = 'kontemplasi_journals';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'content', 'mood', 'is_private', 'created_at'];
    protected $returnType = 'array';
}
