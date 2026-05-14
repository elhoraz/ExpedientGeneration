<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGamification extends Migration
{
    public function up()
    {
        // 1. Menambahkan kolom prestise_points ke tabel users
        $fields = [
            'prestise_points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
        ];
        $this->forge->addColumn('users', $fields);

        // 2. Membuat tabel log prestise (untuk mencegah spam poin)
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
            'activity_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100', // contoh: 'LOGIN_DAILY', 'SYNDICATE_ADD'
            ],
            'points' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('prestise_logs');
    }

    public function down()
    {
        $this->forge->dropTable('prestise_logs', true);
        $this->forge->dropColumn('users', 'prestise_points');
    }
}
