<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetPasswordFields extends Migration
{
    public function up()
    {
        $fields = [
            'reset_password_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '6',
                'null'       => true,
            ],
            'reset_password_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['reset_password_code', 'reset_password_expires']);
    }
}
