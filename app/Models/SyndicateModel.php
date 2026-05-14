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

    // Soft Deletes — bisnis yang dihapus tidak hilang selamanya
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    /**
     * Scope: Menggabungkan data user (nama, foto, WA) ke query syndicate.
     *
     * @return $this
     */
    public function withUser()
    {
        return $this->select('syndicate.*, users.nama_panggilan, users.no_whatsapp, users.foto_profil')
                    ->join('users', 'users.id = syndicate.user_id');
    }
}