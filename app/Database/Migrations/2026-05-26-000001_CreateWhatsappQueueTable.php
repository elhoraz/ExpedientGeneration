<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWhatsappQueueTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'to_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],
            'to_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => '',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'sent', 'failed'],
                'default'    => 'pending',
            ],
            'attempts' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'max_attempts' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'default'    => 3,
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'scheduled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->addKey('scheduled_at');
        $this->forge->createTable('whatsapp_queue', true);
    }

    public function down()
    {
        $this->forge->dropTable('whatsapp_queue', true);
    }
}
