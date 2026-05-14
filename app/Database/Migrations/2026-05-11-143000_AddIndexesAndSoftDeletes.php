<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Penambahan Index Database, Soft Deletes, dan Optimasi Birthday Query
 *
 * Mengatasi masalah performa:
 * 1. Kolom yang sering di-WHERE tanpa index (email, sender_id, user_id)
 * 2. Data terhapus permanen (soft deletes)
 * 3. Birthday query yang tidak bisa menggunakan index (MONTH/DAY function)
 */
class AddIndexesAndSoftDeletes extends Migration
{
    public function up()
    {
        // =======================================
        // 1. SOFT DELETES — Tambah kolom deleted_at
        // =======================================

        // wasiat_messages
        if (!$this->db->fieldExists('deleted_at', 'wasiat_messages')) {
            $this->forge->addColumn('wasiat_messages', [
                'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
                'updated_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            ]);
        }

        // syndicate
        if (!$this->db->fieldExists('deleted_at', 'syndicate')) {
            $this->forge->addColumn('syndicate', [
                'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            ]);
        }

        // =======================================
        // 2. BIRTHDAY OPTIMIZATION — Kolom birth_month_day
        // =======================================
        if (!$this->db->fieldExists('birth_month_day', 'users')) {
            $this->forge->addColumn('users', [
                'birth_month_day' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 5,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'tanggal_lahir',
                    'comment'    => 'Format MM-DD untuk query birthday yang ter-index',
                ],
            ]);

            // Isi data dari tanggal_lahir yang sudah ada
            $this->db->query("UPDATE users SET birth_month_day = DATE_FORMAT(tanggal_lahir, '%m-%d') WHERE tanggal_lahir IS NOT NULL");
        }

        // =======================================
        // 3. DATABASE INDEXES
        // =======================================

        // Index pada users.birth_month_day untuk query birthday harian
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_users_birth_month_day ON users(birth_month_day)");

        // Index pada FK wasiat_messages.sender_id
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_wasiat_sender ON wasiat_messages(sender_id)");

        // Index pada FK syndicate.user_id
        $this->db->query("CREATE INDEX IF NOT EXISTS idx_syndicate_user ON syndicate(user_id)");

        // Index pada users.email (seharusnya UNIQUE, tapi check dulu apakah sudah ada)
        try {
            $this->db->query("CREATE UNIQUE INDEX IF NOT EXISTS idx_users_email ON users(email)");
        } catch (\Exception $e) {
            // Index mungkin sudah ada, abaikan
            log_message('info', 'Index idx_users_email sudah ada atau gagal dibuat: ' . $e->getMessage());
        }
    }

    public function down()
    {
        // Hapus kolom soft delete
        if ($this->db->fieldExists('deleted_at', 'wasiat_messages')) {
            $this->forge->dropColumn('wasiat_messages', 'deleted_at');
        }
        if ($this->db->fieldExists('updated_at', 'wasiat_messages')) {
            $this->forge->dropColumn('wasiat_messages', 'updated_at');
        }
        if ($this->db->fieldExists('deleted_at', 'syndicate')) {
            $this->forge->dropColumn('syndicate', 'deleted_at');
        }

        // Hapus kolom birth_month_day
        if ($this->db->fieldExists('birth_month_day', 'users')) {
            $this->forge->dropColumn('users', 'birth_month_day');
        }

        // Hapus indexes
        try {
            $this->db->query("DROP INDEX idx_users_birth_month_day ON users");
            $this->db->query("DROP INDEX idx_wasiat_sender ON wasiat_messages");
            $this->db->query("DROP INDEX idx_syndicate_user ON syndicate");
            $this->db->query("DROP INDEX idx_users_email ON users");
        } catch (\Exception $e) {
            log_message('info', 'Drop index gagal (mungkin sudah tidak ada): ' . $e->getMessage());
        }
    }
}
