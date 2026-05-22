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
        'nama_lengkap', 'nama_panggilan', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat_lengkap', 'email', 'password_hash', 'no_whatsapp', 'motivasi_hidup',
        'cita_cita', 'akun_ig', 'akun_tiktok', 'foto_profil',
        'email_verify_token', 'email_verified_at', 'webauthn_credential_id',
        'public_token',        // Token publik untuk URL (anti-IDOR)
        'lat', 'lng',          // Koordinat GPS
        'face_data',           // Matriks Wajah Face ID
        'reset_password_code', 'reset_password_expires', // Forgot Password
        'birth_month_day',     // Optimasi query birthday (format MM-DD)
        'remember_token',      // Keep Login token
        'remember_token_expires', // Token expiry date
        // NOTE: 'role', 'is_active', 'prestise_points' SENGAJA tidak disertakan
        // untuk mencegah mass assignment. Update via $db->table() secara eksplisit.
    ];
}