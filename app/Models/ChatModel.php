<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table            = 'chat_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['sender_id', 'receiver_id', 'message', 'is_read'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function withUser()
    {
        $this->select('chat_messages.*, users.nama_lengkap as sender_name, users.foto_profil as sender_avatar');
        $this->join('users', 'users.id = chat_messages.sender_id');
        return $this;
    }

    public function getInbox(int $userId)
    {
        // Mendapatkan user yang pernah chat dengan user saat ini
        // Query akan mengambil pesan terakhir dari setiap percakapan
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT 
                u.id as partner_id, 
                u.nama_panggilan, 
                u.nama_lengkap, 
                u.foto_profil,
                m1.message as last_message,
                m1.created_at as last_message_time
            FROM users u
            JOIN chat_messages m1 ON (u.id = m1.sender_id OR u.id = m1.receiver_id)
            LEFT JOIN chat_messages m2 
                ON (
                    (m2.sender_id = m1.sender_id AND m2.receiver_id = m1.receiver_id) OR
                    (m2.sender_id = m1.receiver_id AND m2.receiver_id = m1.sender_id)
                ) AND m1.created_at < m2.created_at
            WHERE 
                (m1.sender_id = ? OR m1.receiver_id = ?) 
                AND u.id != ?
                AND m1.receiver_id IS NOT NULL
                AND m2.id IS NULL
            GROUP BY u.id
            ORDER BY m1.created_at DESC
        ";
        
        return $db->query($sql, [$userId, $userId, $userId])->getResultArray();
    }
}
