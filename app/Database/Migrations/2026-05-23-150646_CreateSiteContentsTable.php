<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiteContentsTable extends Migration
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
            'content_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'content_value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'content_type' => [
                'type'       => 'ENUM',
                'constraint' => ['text', 'image', 'html'],
                'default'    => 'text',
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
        $this->forge->createTable('site_contents', true);
    }

    public function down()
    {
        $this->forge->dropTable('site_contents');
    }
}
