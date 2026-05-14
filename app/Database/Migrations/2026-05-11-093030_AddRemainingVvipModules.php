<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRemainingVvipModules extends Migration
{
    public function up()
    {
        // 8. Celestial Codex (Tarot Cards)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'numeral' => ['type' => 'VARCHAR', 'constraint' => '10'],
            'symbol'  => ['type' => 'VARCHAR', 'constraint' => '50'],
            'name'    => ['type' => 'VARCHAR', 'constraint' => '100'],
            'meaning' => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('celestial_cards', true);

        // 9. Divine Verses (Quran/Verses)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'arabic'  => ['type' => 'TEXT'],
            'latin'   => ['type' => 'TEXT'],
            'meaning' => ['type' => 'TEXT'],
            'source'  => ['type' => 'VARCHAR', 'constraint' => '100'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('divine_verses', true);

        // 10. Tarbiyah Nexus (Mentors & Tenders)
        // 10.a Mentors
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name'        => ['type' => 'VARCHAR', 'constraint' => '100'],
            'role'        => ['type' => 'VARCHAR', 'constraint' => '100'],
            'description' => ['type' => 'TEXT'],
            'avatar_url'  => ['type' => 'VARCHAR', 'constraint' => '255'],
            'slots'       => ['type' => 'INT', 'constraint' => 11, 'default' => 3],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tarbiyah_mentors', true);

        // 10.b Tenders
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title'          => ['type' => 'VARCHAR', 'constraint' => '255'],
            'company'        => ['type' => 'VARCHAR', 'constraint' => '255'],
            'description'    => ['type' => 'TEXT'],
            'classification' => ['type' => 'VARCHAR', 'constraint' => '10', 'default' => 'A'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tarbiyah_tenders', true);

        // 10.c Requests/Applications
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'target_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // ID of Mentor or Tender
            'type'       => ['type' => 'ENUM', 'constraint' => ['Mentorship', 'Tender']],
            'status'     => ['type' => 'ENUM', 'constraint' => ['Pending', 'Approved', 'Rejected'], 'default' => 'Pending'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tarbiyah_requests', true);

        // 11. Genesis Core Logs
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'event'      => ['type' => 'VARCHAR', 'constraint' => '100', 'default' => 'Singularity'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('genesis_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('genesis_logs', true);
        $this->forge->dropTable('tarbiyah_requests', true);
        $this->forge->dropTable('tarbiyah_tenders', true);
        $this->forge->dropTable('tarbiyah_mentors', true);
        $this->forge->dropTable('divine_verses', true);
        $this->forge->dropTable('celestial_cards', true);
    }
}
