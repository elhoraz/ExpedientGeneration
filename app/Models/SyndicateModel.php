<?php

namespace App\Models;

use CodeIgniter\Model;

class SyndicateModel extends Model
{
    protected $table            = 'syndicate';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Gerbang kolom yang diizinkan untuk diisi
    protected $allowedFields    = [
        'user_id', 
        'nama_bisnis', 
        'kategori', 
        'deskripsi', 
        'link_url', 
        'logo_bisnis'
    ];

    // Aktifkan pengisian waktu otomatis (created_at & updated_at)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}