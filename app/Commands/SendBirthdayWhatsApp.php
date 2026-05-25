<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserModel;
use App\Services\WhatsAppService;

/**
 * SendBirthdayWhatsApp
 *
 * Spark command untuk kirim WA ucapan ulang tahun personal.
 * Dijalankan via cron setiap hari pukul 07:00:
 *   0 7 * * * cd /path/to/project && php spark wa:birthday >> /dev/null 2>&1
 */
class SendBirthdayWhatsApp extends BaseCommand
{
    protected $group       = 'WhatsApp';
    protected $name        = 'wa:birthday';
    protected $description = 'Kirim ucapan ulang tahun via WhatsApp ke alumni yang berulang tahun hari ini.';
    protected $usage       = 'wa:birthday [--dry-run]';
    protected $arguments   = [];
    protected $options     = [
        '--dry-run' => 'Tampilkan daftar tanpa mengirim',
    ];

    public function run(array $params)
    {
        $dryRun = isset($params['dry-run']) || CLI::getOption('dry-run');

        CLI::write('🎂 Mengecek ulang tahun hari ini...', 'yellow');

        $userModel = new UserModel();

        $birthdayUsers = $userModel
            ->where('MONTH(tanggal_lahir)', date('m'))
            ->where('DAY(tanggal_lahir)', date('d'))
            ->where('no_whatsapp IS NOT NULL', null, false)
            ->where('no_whatsapp !=', '')
            ->where('email_verified_at IS NOT NULL', null, false)
            ->findAll();

        if (empty($birthdayUsers)) {
            CLI::write('Tidak ada alumni yang berulang tahun hari ini.', 'light_gray');
            return;
        }

        CLI::write(count($birthdayUsers) . ' alumni berulang tahun hari ini:', 'green');
        CLI::newLine();

        $waService = new WhatsAppService();
        $sent      = 0;
        $failed    = 0;

        foreach ($birthdayUsers as $user) {
            $nama  = $user['nama_panggilan'];
            $nomor = $user['no_whatsapp'];

            // Hitung usia
            $usia = 0;
            if (!empty($user['tanggal_lahir'])) {
                $birth = new \DateTime($user['tanggal_lahir']);
                $today = new \DateTime();
                $usia  = (int) $today->diff($birth)->y;
            }

            CLI::write("  🎂 {$nama} ({$nomor}) — Usia {$usia}", 'white');

            if ($dryRun) {
                CLI::write("     [DRY-RUN] Tidak dikirim", 'light_gray');
                continue;
            }

            $result = $waService->sendBirthdayWish($nomor, $nama, $usia);

            if ($result['success']) {
                CLI::write("     ✅ Terkirim", 'green');
                $sent++;
            } else {
                CLI::write("     ❌ Gagal: " . $result['error'], 'red');
                $failed++;
            }

            usleep(500000); // 500ms antar kirim
        }

        CLI::newLine();
        if (!$dryRun) {
            CLI::write("Selesai — Terkirim: {$sent}, Gagal: {$failed}", 'cyan');
        } else {
            CLI::write("[DRY-RUN] Tidak ada pesan yang dikirim.", 'yellow');
        }
    }
}
