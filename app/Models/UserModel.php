<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Fitur otomatis untuk created_at dan updated_at
    protected $useTimestamps    = true; 
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Daftar kolom yang diizinkan untuk diisi (Proteksi Mass Assignment)
    protected $allowedFields = [
    'nama_lengkap', 'nama_panggilan', 'jenis_kelamin', 'tempat_tanggal_lahir', 
    'alamat_lengkap', 'email', 'password_hash', 'no_whatsapp', 'motivasi_hidup', 
    'cita_cita', 'akun_ig', 'akun_tiktok', 'foto_profil', 
    'email_verify_token', 'email_verified_at', 'webauthn_credential_id','lat', // <--- Gerbang Latitude (Garis Lintang)
        'lng', // <--- Gerbang Longitude (Garis Bujur)
        'face_data' // <--- [BARU] Gerbang Matriks Wajah Face ID
    ];
}