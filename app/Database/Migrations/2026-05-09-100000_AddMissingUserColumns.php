<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingUserColumns extends Migration
{
    public function up()
    {
        $fields = [
            'webauthn_credential_id' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'lat' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
            ],
            'lng' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => true,
            ],
            'face_data' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
        ];

        // Memastikan tabel users ada sebelum menambah kolom
        if ($this->db->tableExists('users')) {
            $fieldsToAdd = [];
            foreach ($fields as $fieldName => $fieldConfig) {
                if (!$this->db->fieldExists($fieldName, 'users')) {
                    $fieldsToAdd[$fieldName] = $fieldConfig;
                }
            }

            if (!empty($fieldsToAdd)) {
                $this->forge->addColumn('users', $fieldsToAdd);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users')) {
            $this->forge->dropColumn('users', ['webauthn_credential_id', 'lat', 'lng', 'face_data']);
        }
    }
}
