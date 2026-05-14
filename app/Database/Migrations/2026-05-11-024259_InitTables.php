<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitTables extends Migration
{
    public function up()
    {
        // ===============================================
        // 1. TABLE: users
        // ===============================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'nama_panggilan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['Laki-laki', 'Perempuan'],
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
            ],
            'tempat_tanggal_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'alamat_lengkap' => [
                'type' => 'TEXT',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'no_whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'motivasi_hidup' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cita_cita' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'akun_ig' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'akun_tiktok' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'face_data' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'webauthn_credential_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'lat' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
            ],
            'lng' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'email_verify_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'email_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'reset_password_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'reset_password_expires' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->createTable('users', true); // create if not exists

        // ===============================================
        // 2. TABLE: syndicate
        // ===============================================
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
            ],
            'nama_bisnis' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
            ],
            'logo_bisnis' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'link_url' => [
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('syndicate', true);

        // ===============================================
        // 3. TABLE: buku_tamu
        // ===============================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
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
        $this->forge->createTable('buku_tamu', true);

        // ===============================================
        // 4. TABLE: activity_logs
        // ===============================================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // bisa null jika aksi dilakukan guest
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activity_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('activity_logs', true);
        $this->forge->dropTable('buku_tamu', true);
        $this->forge->dropTable('syndicate', true);
        $this->forge->dropTable('users', true);
    }
}
