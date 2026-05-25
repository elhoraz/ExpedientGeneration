<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBerandaGalleryTable extends Migration
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
            'image_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'caption' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('beranda_gallery', true);
    }

    public function down()
    {
        $this->forge->dropTable('beranda_gallery');
    }
}
