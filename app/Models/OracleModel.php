<?php
namespace App\Models;
use CodeIgniter\Model;

class OracleModel extends Model
{
    protected $table = 'oracle_visions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'vision_text', 'unlock_date', 'is_unlocked', 'created_at'];
    protected $returnType = 'array';
}
