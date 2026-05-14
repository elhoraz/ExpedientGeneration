<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuTamuModel extends Model
{
    protected $table            = 'buku_tamu';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'nama', 
        'pesan', 
        'created_at'
    ];

    // Karena tabel ini cuma punya created_at (tanpa updated_at), kita pakai array custom untuk insert
    // Namun untuk clean CodeIgniter 4, lebih baik tidak pakai timestamps auto jika fieldnya beda
    // atau biarkan model yang ngurus secara manual sebelum insert
}
