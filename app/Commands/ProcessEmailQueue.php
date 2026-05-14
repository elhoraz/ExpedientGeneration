<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\EmailQueueService;

/**
 * ProcessEmailQueue
 * 
 * Spark command untuk memproses antrian email.
 * Dijalankan via cron job setiap 1 menit:
 *   * * * * * cd /path/to/project && php spark email:process
 */
class ProcessEmailQueue extends BaseCommand
{
    protected $group       = 'Email';
    protected $name        = 'email:process';
    protected $description = 'Memproses antrian email yang menunggu dikirim.';
    protected $usage       = 'email:process [--limit=10]';
    protected $arguments   = [];
    protected $options     = [
        '--limit' => 'Jumlah email per batch (default: 10)',
    ];

    public function run(array $params)
    {
        $limit = (int) ($params['limit'] ?? CLI::getOption('limit') ?? 10);

        CLI::write('📧 Memproses antrian email...', 'yellow');
        CLI::write("   Batch limit: {$limit}", 'light_gray');

        $service = new EmailQueueService();
        $stats = $service->processQueue($limit);

        CLI::newLine();
        CLI::write("✅ Terkirim : {$stats['sent']}", 'green');
        CLI::write("❌ Gagal    : {$stats['failed']}", 'red');
        CLI::write("⏭️  Dilewati : {$stats['skipped']}", 'light_gray');
        CLI::newLine();

        if ($stats['sent'] + $stats['failed'] === 0) {
            CLI::write('Tidak ada email dalam antrian.', 'light_gray');
        }
    }
}
