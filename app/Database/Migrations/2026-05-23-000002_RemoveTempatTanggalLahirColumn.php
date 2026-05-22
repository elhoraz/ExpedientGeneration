<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveTempatTanggalLahirColumn extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('users', 'tempat_tanggal_lahir');
    }

    public function down()
    {
        $this->forge->addColumn('users', [
            'tempat_tanggal_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }
}
