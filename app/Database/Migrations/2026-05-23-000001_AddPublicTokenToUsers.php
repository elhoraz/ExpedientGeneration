<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPublicTokenToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'public_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        // Generate unique tokens for existing users
        $db = \Config\Database::connect();
        $users = $db->table('users')->select('id')->get()->getResultArray();

        foreach ($users as $user) {
            $token = bin2hex(random_bytes(16)); // 32 char hex token
            $db->table('users')->where('id', $user['id'])->update(['public_token' => $token]);
        }

        // Now add unique index
        $this->forge->addKey('public_token', false, true); // (column, primary=false, unique=true)
        $this->forge->processIndexes('users');
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'public_token');
    }
}
