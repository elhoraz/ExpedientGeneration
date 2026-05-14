<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTanggalLahirColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'tanggal_lahir' => [
                'type'   => 'DATE',
                'null'   => true,
                'after'  => 'tempat_tanggal_lahir',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'tanggal_lahir');
    }
}
