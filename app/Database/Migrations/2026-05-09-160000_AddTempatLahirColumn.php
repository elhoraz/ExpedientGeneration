<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTempatLahirColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'tempat_tanggal_lahir',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'tempat_lahir');
    }
}
