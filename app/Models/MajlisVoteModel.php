<?php
namespace App\Models;
use CodeIgniter\Model;

class MajlisVoteModel extends Model
{
    protected $table = 'majlis_votes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['topic_id', 'user_id', 'vote_choice', 'created_at'];
    protected $returnType = 'array';
}
