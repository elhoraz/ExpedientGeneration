<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            // Cek apakah ada cookie remember_me
            helper('cookie');
            $token = get_cookie('remember_me');

            if ($token) {
                $userModel = new \App\Models\UserModel();
                $user = $userModel
                    ->where('remember_token', $token)
                    ->where('remember_token_expires >', date('Y-m-d H:i:s'))
                    ->first();

                if ($user) {
                    // Auto Login
                    $sesData = [
                        'user_id'         => $user['id'],
                        'nama_panggilan' => $user['nama_panggilan'],
                        'email'          => $user['email'],
                        'logged_in'      => TRUE
                    ];
                    session()->set($sesData);
                    return; // Lanjutkan request
                }
            }

            return redirect()->to('/login')->with('error', 'Akses ditolak! Silakan login terlebih dahulu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelah halaman dimuat
    }
}