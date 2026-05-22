<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRememberTokenExpires extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'remember_token_expires' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
                'after'   => 'remember_token',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'remember_token_expires');
    }
}
