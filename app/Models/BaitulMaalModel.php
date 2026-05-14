<?php
namespace App\Models;
use CodeIgniter\Model;

class BaitulMaalModel extends Model
{
    protected $table = 'baitul_maal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'amount', 'transaction_type', 'description', 'proof_image', 'created_at'];
    protected $returnType = 'array';
}
