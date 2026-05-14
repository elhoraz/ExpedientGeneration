<?php

namespace App\Services;

use App\Models\EmailQueueModel;

/**
 * EmailQueueService
 * 
 * Mengelola antrian email untuk pengiriman asinkron.
 * Email disimpan ke tabel `email_queue` dan diproses oleh
 * CLI command `php spark email:process`.
 * 
 * Usage:
 *   $emailQueue = new EmailQueueService();
 *   $emailQueue->enqueue('user@example.com', 'Subject', '<h1>Body</h1>');
 */
class EmailQueueService
{
    protected EmailQueueModel $model;

    public function __construct()
    {
        $this->model = new EmailQueueModel();
    }

    /**
     * Tambahkan email ke antrian.
     */
    public function enqueue(string $toEmail, string $subject, string $body, string $toName = '', ?string $scheduledAt = null): int
    {
        $this->model->insert([
            'to_email'     => $toEmail,
            'to_name'      => $toName,
            'subject'      => $subject,
            'body'         => $body,
            'status'       => 'pending',
            'attempts'     => 0,
            'max_attempts' => 3,
            'scheduled_at' => $scheduledAt ?? date('Y-m-d H:i:s'),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->model->getInsertID();
    }

    /**
     * Proses email dari antrian.
     * Mengambil email pending, mengirim via SMTP, update status.
     * 
     * @param int $limit Jumlah email yang diproses per batch
     * @return array Statistik [sent, failed, skipped]
     */
    public function processQueue(int $limit = 10): array
    {
        $stats = ['sent' => 0, 'failed' => 0, 'skipped' => 0];

        $emails = $this->model
            ->where('status', 'pending')
            ->where('scheduled_at <=', date('Y-m-d H:i:s'))
            ->where('attempts <', 3)
            ->orderBy('created_at', 'ASC')
            ->limit($limit)
            ->findAll();

        if (empty($emails)) {
            return $stats;
        }

        $emailService = \Config\Services::email();

        foreach ($emails as $email) {
            try {
                $emailService->clear();
                $emailService->setFrom(env('email.SMTPUser', 'noreply@expedient.com'), 'Expedient Generation');
                $emailService->setTo($email['to_email']);
                $emailService->setSubject($email['subject']);
                $emailService->setMessage($email['body']);

                if ($emailService->send()) {
                    $this->model->update($email['id'], [
                        'status'  => 'sent',
                        'sent_at' => date('Y-m-d H:i:s'),
                    ]);
                    $stats['sent']++;
                } else {
                    $this->markFailed($email, $emailService->printDebugger(['headers']));
                    $stats['failed']++;
                }
            } catch (\Exception $e) {
                $this->markFailed($email, $e->getMessage());
                $stats['failed']++;
            }
        }

        return $stats;
    }

    /**
     * Tandai email gagal & increment attempts.
     */
    protected function markFailed(array $email, string $error): void
    {
        $attempts = $email['attempts'] + 1;
        $status = $attempts >= $email['max_attempts'] ? 'failed' : 'pending';

        $this->model->update($email['id'], [
            'attempts'      => $attempts,
            'status'        => $status,
            'error_message' => substr($error, 0, 1000),
        ]);
    }
}
