<?php
namespace App\Models;
use CodeIgniter\Model;

class WasiatModel extends Model
{
    protected $table = 'wasiat_messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['sender_id', 'passphrase_hash', 'encrypted_message', 'file_attachment', 'created_at'];
    protected $returnType = 'array';

    // Soft Deletes — wasiat yang dihapus tidak hilang selamanya
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Scope: Menggabungkan data user (nama, foto) ke query wasiat.
     * Menghilangkan kebutuhan raw DB query di controller.
     *
     * @return $this
     */
    public function withUser()
    {
        return $this->select('wasiat_messages.*, users.nama_panggilan, users.foto_profil')
                    ->join('users', 'users.id = wasiat_messages.sender_id');
    }
}
