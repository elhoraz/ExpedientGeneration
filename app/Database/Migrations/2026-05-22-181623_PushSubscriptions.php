<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PushSubscriptions extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'endpoint' => [
                'type' => 'TEXT',
            ],
            'auth_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'p256dh_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('push_subscriptions', true);
    }

    public function down()
    {
        $this->forge->dropTable('push_subscriptions', true);
    }
}
