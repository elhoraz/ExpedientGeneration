<?php
namespace App\Models;
use CodeIgniter\Model;

class MajlisTopicModel extends Model
{
    protected $table = 'majlis_topics';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'created_by', 'status', 'created_at'];
    protected $returnType = 'array';
}
