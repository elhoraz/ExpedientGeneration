<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class WorkEmailQueue extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Queue';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'queue:work-email';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Memproses antrean email di latar belakang.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'queue:work-email';

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Mencari antrean email...', 'yellow');

        $db = \Config\Database::connect();
        
        // Memeriksa keberadaan tabel email_queue
        if ($db->tableExists('email_queue')) {
            $builder = $db->table('email_queue');
            $queues = $builder->where('status', 'pending')
                              ->orderBy('created_at', 'ASC')
                              ->limit(50)
                              ->get()
                              ->getResultArray();

            if (empty($queues)) {
                CLI::write('Tidak ada email di antrean.', 'green');
                return;
            }

            $email = \Config\Services::email();

            foreach ($queues as $q) {
                CLI::write("Memproses email ke: {$q['to_email']}", 'yellow');
                
                $email->clear();
                $email->setTo($q['to_email']);
                $email->setSubject($q['subject']);
                $email->setMessage($q['message']);

                if ($email->send()) {
                    CLI::write("Email terkirim ke {$q['to_email']}", 'green');
                    $builder->where('id', $q['id'])->update([
                        'status' => 'sent',
                        'sent_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    CLI::write("Gagal mengirim ke {$q['to_email']}", 'red');
                    $builder->where('id', $q['id'])->update([
                        'status' => 'failed',
                        'error_message' => $email->printDebugger(['headers'])
                    ]);
                }
            }
        } else {
            CLI::write('Tabel email_queue belum dibuat. Silakan buat migrasi terlebih dahulu.', 'red');
        }
        
        CLI::write('Proses antrean selesai.', 'green');
    }
}
