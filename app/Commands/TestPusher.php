<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestPusher extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:pusher';
    protected $description = 'Test Pusher Cloud';

    public function run(array $params)
    {
        $pusherService = \Config\Services::pusherService();
        $result = $pusherService->sendChatMessage(['test' => 1]);
        CLI::write("Pusher result: " . ($result ? "true" : "false"));
    }
}
