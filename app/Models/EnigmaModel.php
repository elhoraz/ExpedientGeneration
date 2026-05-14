<?php
namespace App\Models;
use CodeIgniter\Model;

class EnigmaModel extends Model
{
    protected $table = 'enigma_progress';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'puzzle_seed', 'current_level', 'is_completed', 'completed_at'];
    protected $returnType = 'array';
}
