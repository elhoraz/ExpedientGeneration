<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddChatImageColumn extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('image_path', 'chat_messages')) {
            $this->forge->addColumn('chat_messages', [
                'image_path' => [
                    'type'    => 'VARCHAR',
                    'constraint' => 255,
                    'null'    => true,
                    'default' => null,
                    'after'   => 'message',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('image_path', 'chat_messages')) {
            $this->forge->dropColumn('chat_messages', 'image_path');
        }
    }
}
