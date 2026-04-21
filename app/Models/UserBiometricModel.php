<?php

namespace App\Models;

use CodeIgniter\Model;

class UserBiometricModel extends Model
{
    protected $table            = 'user_biometrics';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Kosongkan karena biometrik jarang butuh update time

    protected $allowedFields    = [
        'user_id', 
        'credential_id', 
        'public_key', 
        'sign_count'
    ];
}