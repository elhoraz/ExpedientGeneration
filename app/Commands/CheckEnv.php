<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckEnv extends BaseCommand
{
    protected $group       = 'Config';
    protected $name        = 'env:check';
    protected $description = 'Check Pusher env vars.';

    public function run(array $params)
    {
        CLI::write("PUSHER_HOST: " . env('PUSHER_HOST'));
        CLI::write("PUSHER_APP_KEY: " . env('PUSHER_APP_KEY'));
        CLI::write("PUSHER_SCHEME: " . env('PUSHER_SCHEME'));
    }
}
