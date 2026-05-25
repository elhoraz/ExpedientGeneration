<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SecurityHeaders implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do nothing
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if ($response instanceof \CodeIgniter\HTTP\Response) {
            // Content Security Policy
            $csp = "default-src 'self'; ";
            $csp .= "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://www.youtube.com https://s.ytimg.com; ";
            $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; ";
            $csp .= "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; ";
            $csp .= "img-src 'self' data: https: blob:; ";
            $csp .= "connect-src 'self' https://api.bigdatacloud.net https://cdn.jsdelivr.net https://unpkg.com https://*.tile.openstreetmap.org https://*.pusher.com wss://*.pusher.com; ";
            $csp .= "media-src 'self' blob: https://audio.qurancdn.com https://everyayah.com https://actions.google.com https://server8.mp3quran.net; ";
            $csp .= "frame-src 'self' https://www.youtube.com;";

            $response->setHeader('Content-Security-Policy', $csp);
            
            // Keamanan HTTP Lainnya
            $response->setHeader('X-Content-Type-Options', 'nosniff');
            $response->setHeader('X-Frame-Options', 'SAMEORIGIN');
            $response->setHeader('X-XSS-Protection', '1; mode=block');
            $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        }
    }
}
