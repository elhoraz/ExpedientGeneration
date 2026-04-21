<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthTables extends Migration
{
    public function up()
    {
        // ==========================================
        // 1. TABEL USERS (Data Alumni Expedient)
        // ==========================================
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
            'tempat_tanggal_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '150', // Digabung sesuai request, misal: "Jakarta, 17 Agustus 1999"
            ],
            'alamat_lengkap' => [
                'type' => 'TEXT',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true, // Pastikan email tidak boleh ganda
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
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
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
                'null'       => true, // Nullable di awal sebelum user upload
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
        $this->forge->createTable('users');

        // ==========================================
        // 2. TABEL USER BIOMETRICS (Sidik Jari/FaceID)
        // ==========================================
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
                'unsigned'       => true,
            ],
            'credential_id' => [
                'type' => 'TEXT', // ID Unik dari perangkat biometrik
            ],
            'public_key' => [
                'type' => 'TEXT', // Kunci publik untuk verifikasi tanpa password
            ],
            'sign_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        // Menambahkan relasi (Foreign Key) agar terhubung ke tabel users
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_biometrics');
    }

    public function down()
    {
        // Harus urut dari biometrics dulu karena ada foreign key
        $this->forge->dropTable('user_biometrics');
        $this->forge->dropTable('users');
    }
}