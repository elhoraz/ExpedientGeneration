<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsReadToChatMessages extends Migration
{
    public function up()
    {
        $fields = [
            'is_read' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ];
        $this->forge->addColumn('chat_messages', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('chat_messages', 'is_read');
        $this->forge->dropColumn('chat_messages', 'is_deleted');
    }
}
