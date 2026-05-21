<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminColumns extends Migration
{
    public function up()
    {
        // Tambah kolom is_active di tabel users
        if (!$this->db->fieldExists('is_active', 'users')) {
            $this->forge->addColumn('users', [
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'after'      => 'role',
                ],
            ]);
        }

        // Tambah kolom is_deleted di tabel chat_messages (soft delete)
        if (!$this->db->fieldExists('is_deleted', 'chat_messages')) {
            $this->forge->addColumn('chat_messages', [
                'is_deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    //after'      => 'is_read',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('is_active', 'users')) {
            $this->forge->dropColumn('users', 'is_active');
        }
        if ($this->db->fieldExists('is_deleted', 'chat_messages')) {
            $this->forge->dropColumn('chat_messages', 'is_deleted');
        }
    }
}
