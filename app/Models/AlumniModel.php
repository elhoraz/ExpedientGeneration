<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumniModel extends Model
{
    protected $table            = 'alumni';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // FIELD YANG DIIZINKAN UNTUK DIISI/DIUBAH (Sangat Penting!)
    protected $allowedFields    = [
        'nama_lengkap', 
        'nama_panggilan', 
        'tempat_tanggal_lahir', 
        'alamat_lengkap', 
        'cita_cita', 
        'motivasi_hidup', 
        'akun_ig', 
        'akun_tiktok', 
        'foto_profil',
        'lat', // Kolom Latitude Radar
        'lng'  // Kolom Longitude Radar
    ];

    // Aktifkan timestamp otomatis untuk created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}