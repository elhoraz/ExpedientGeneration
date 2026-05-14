<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBukuTamuTable extends Migration
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
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'pesan' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('buku_tamu', true); // IF NOT EXISTS
    }

    public function down()
    {
        $this->forge->dropTable('buku_tamu');
    }
}
