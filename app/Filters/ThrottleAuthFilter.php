<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ThrottleAuthFilter
 * 
 * Middleware rate-limiting untuk melindungi endpoint Autentikasi dari Brute-Force.
 * Membatasi maksimal 5 request per menit per IP address.
 * Return Redirect back dengan pesan error jika melebihi limit.
 */
class ThrottleAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = \Config\Services::throttler();

        // Buat key unik berdasarkan IP + path
        $key = 'auth_throttle_' . md5($request->getIPAddress() . '_' . $request->getPath());

        // Maksimal 5 request per menit per IP per endpoint auth
        if ($throttler->check($key, 5, MINUTE) === false) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan. Akses diblokir sementara untuk keamanan. Silakan coba lagi dalam 1 menit.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
