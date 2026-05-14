<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class VisitorTrackingFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Hanya melacak permintaan GET (biasanya tampilan halaman)
        // Dan abaikan API atau request CLI
        if ($request->getMethod() !== 'get' || $request->isCLI()) {
            return;
        }

        $db = \Config\Database::connect();
        
        $userId = session()->get('user_id') ?: null;
        $pageUrl = (string) $request->getUri();
        $ipAddress = $request->getIPAddress();
        $userAgent = $request->getUserAgent()->getAgentString();

        // Jangan melacak asset statis (jika filter ini aktif global)
        // Namun jika diaktifkan hanya pada rute tertentu, kita bisa langsung insert.
        // Kita akan menggunakan filter global, jadi pastikan kita hanya melacak controller/halaman.
        if (strpos($pageUrl, 'assets/') !== false || strpos($pageUrl, 'css/') !== false || strpos($pageUrl, 'js/') !== false) {
            return;
        }

        $db->table('page_visits')->insert([
            'user_id'    => $userId,
            'page_url'   => $pageUrl,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'visited_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
