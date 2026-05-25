<?php

namespace App\Controllers;

use App\Services\WhatsAppService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class WhatsAppAdminController extends BaseController
{
    protected WhatsAppService $waService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->waService = service('whatsAppService');
    }

    /**
     * Dashboard antrian WhatsApp.
     */
    public function index()
    {
        $data = [
            'title'       => 'WhatsApp Broadcast',
            'stats'       => $this->waService->getQueueStats(),
            'recent_logs' => $this->waService->getRecentQueue(50),
        ];

        return view('admin/whatsapp/index', $data);
    }

    /**
     * Manual blast ke semua alumni dengan pesan kustom.
     */
    public function blast()
    {
        $message = $this->request->getPost('message');

        if (empty(trim((string)$message))) {
            return redirect()->to('/admin/whatsapp')->with('error', 'Pesan tidak boleh kosong.');
        }

        if (strlen($message) > 4096) {
            return redirect()->to('/admin/whatsapp')->with('error', 'Pesan terlalu panjang (maks 4096 karakter).');
        }

        $count = $this->waService->broadcastToAll(trim($message));

        if ($count === 0) {
            return redirect()->to('/admin/whatsapp')
                ->with('error', 'Tidak ada alumni yang opt-in notifikasi WA, atau semua nomor WA kosong.');
        }

        return redirect()->to('/admin/whatsapp')
            ->with('success', "✅ Pesan berhasil ditambahkan ke antrian untuk {$count} alumni. Jalankan <code>php spark wa:process</code> atau tunggu cron.");
    }

    /**
     * Reset pesan yang failed agar bisa dicoba ulang.
     */
    public function retryFailed()
    {
        $db = \Config\Database::connect();
        $db->table('whatsapp_queue')
            ->where('status', 'failed')
            ->update(['status' => 'pending', 'attempts' => 0]);

        $affected = $db->affectedRows();

        return redirect()->to('/admin/whatsapp')
            ->with('success', "🔄 {$affected} pesan gagal berhasil di-reset ke pending.");
    }

    /**
     * Proses antrian sekarang (tanpa menunggu cron).
     * Berguna untuk testing & manual trigger.
     */
    public function processNow()
    {
        $stats = $this->waService->processQueue(50);

        $msg = "📤 Proses selesai — Terkirim: {$stats['sent']}, Gagal: {$stats['failed']}";
        if ($stats['sent'] === 0 && $stats['failed'] > 0) {
            // Ada yang gagal — mungkin token tidak valid
            $msg .= '. Cek log error atau status token Fonnte di bawah.';
        }

        return redirect()->to('/admin/whatsapp')->with('success', $msg);
    }

    /**
     * Diagnostik: Cek status koneksi & token Fonnte secara real-time.
     */
    public function diagnose()
    {
        $token  = env('FONNTE_TOKEN', '');
        $result = ['token_set' => !empty($token), 'api_ok' => false, 'reason' => '', 'raw' => ''];

        if (!empty($token)) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => 'https://api.fonnte.com/device',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER     => ['Authorization: ' . $token],
                CURLOPT_POSTFIELDS     => [],
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            $result['raw']      = $response;
            $result['http']     = $httpCode;
            $result['curl_err'] = $curlErr;

            $decoded = json_decode($response, true);
            if ($decoded && isset($decoded['status'])) {
                $result['api_ok'] = $decoded['status'] === true;
                $result['reason'] = $decoded['reason'] ?? ($decoded['name'] ?? '');
            }
        }

        return $this->response->setJSON([
            'token_set'   => $result['token_set'],
            'token_value' => $result['token_set'] ? substr($token, 0, 8) . '...' : '(kosong)',
            'api_ok'      => $result['api_ok'],
            'http_code'   => $result['http'] ?? null,
            'curl_error'  => $result['curl_err'] ?? null,
            'reason'      => $result['reason'],
            'raw_response'=> $result['raw'],
        ]);
    }
}
