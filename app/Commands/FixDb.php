<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixDb extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:fix';
    protected $description = 'Fix chat_messages table columns.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        $fields = $db->getFieldNames('chat_messages');
        if (!in_array('is_read', $fields)) {
            $forge->addColumn('chat_messages', [
                'is_read' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]
            ]);
            CLI::write('Added is_read', 'green');
        } else {
            CLI::write('is_read exists', 'yellow');
        }

        if (!in_array('is_deleted', $fields)) {
            $forge->addColumn('chat_messages', [
                'is_deleted' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]
            ]);
            CLI::write('Added is_deleted', 'green');
        } else {
            CLI::write('is_deleted exists', 'yellow');
        }
    }
}
