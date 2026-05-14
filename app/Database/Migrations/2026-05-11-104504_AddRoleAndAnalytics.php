<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleAndAnalytics extends Migration
{
    public function up()
    {
        // 1. Menambahkan kolom role ke tabel users
        $fields = [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['user', 'admin'],
                'default'    => 'user',
                'null'       => false,
            ],
        ];
        $this->forge->addColumn('users', $fields);

        // 2. Membuat tabel pelacakan kunjungan (page_visits)
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
                'null'       => true, // Null berarti tamu/pengunjung tidak login
            ],
            'page_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'visited_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('page_visits');

        // Mengatur role admin untuk email tertentu (menggunakan Query Langsung jika data sudah ada)
        $db = \Config\Database::connect();
        $db->query("UPDATE users SET role = 'admin' WHERE email = 'muhammad.nurtaufiqi3@gmail.com'");
    }

    public function down()
    {
        $this->forge->dropTable('page_visits', true);
        $this->forge->dropColumn('users', 'role');
    }
}
