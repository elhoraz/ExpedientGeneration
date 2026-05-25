<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\WhatsAppService;

/**
 * ProcessWhatsAppQueue
 *
 * Spark command untuk memproses antrian WhatsApp.
 * Dijalankan via cron job setiap 1 menit:
 *   * * * * * cd /path/to/project && php spark wa:process >> /dev/null 2>&1
 */
class ProcessWhatsAppQueue extends BaseCommand
{
    protected $group       = 'WhatsApp';
    protected $name        = 'wa:process';
    protected $description = 'Memproses antrian WhatsApp yang menunggu dikirim.';
    protected $usage       = 'wa:process [--limit=20]';
    protected $arguments   = [];
    protected $options     = [
        '--limit' => 'Jumlah pesan per batch (default: 20)',
    ];

    public function run(array $params)
    {
        $limit = (int) ($params['limit'] ?? CLI::getOption('limit') ?? 20);

        CLI::write('📱 Memproses antrian WhatsApp...', 'yellow');
        CLI::write("   Batch limit: {$limit}", 'light_gray');

        $service = new WhatsAppService();
        $stats   = $service->processQueue($limit);

        CLI::newLine();
        CLI::write("✅ Terkirim : {$stats['sent']}", 'green');
        CLI::write("❌ Gagal    : {$stats['failed']}", 'red');
        CLI::write("⏭️  Dilewati : {$stats['skipped']}", 'light_gray');
        CLI::newLine();

        if ($stats['sent'] + $stats['failed'] === 0) {
            CLI::write('Tidak ada pesan WhatsApp dalam antrian.', 'light_gray');
        }
    }
}
