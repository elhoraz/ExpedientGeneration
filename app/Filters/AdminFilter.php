<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($session->get('user_id'));

        if (!$user || $user['role'] !== 'admin') {
            // Tolak akses jika bukan admin
            return redirect()->to('/beranda')->with('error', 'Akses ditolak: Protokol keamanan mendeteksi Anda bukan Administrator.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
