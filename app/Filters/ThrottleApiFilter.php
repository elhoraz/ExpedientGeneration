<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ThrottleApiFilter
 * 
 * Middleware rate-limiting untuk melindungi endpoint API/AJAX dari spam.
 * Membatasi maksimal 10 request per menit per IP address.
 * Return JSON 429 (Too Many Requests) jika melebihi limit.
 */
class ThrottleApiFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = \Config\Services::throttler();

        // Buat key unik berdasarkan IP + path agar setiap endpoint punya limit sendiri
        $key = 'api_throttle_' . md5($request->getIPAddress() . '_' . $request->getPath());

        // Maksimal 10 request per menit per IP per endpoint
        if ($throttler->check($key, 10, MINUTE) === false) {
            return \Config\Services::response()
                ->setStatusCode(429)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Terlalu banyak permintaan. Silakan coba lagi dalam 1 menit.',
                    'code'    => 429
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah response
    }
}
