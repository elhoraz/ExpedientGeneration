<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class UnlockController extends BaseController
{
    public function index()
    {
        // If already unlocked, redirect to dashboard
        if (session()->get('admin_unlocked') === true) {
            return redirect()->to('/admin/dashboard');
        }

        $data = [
            'title' => 'Admin Dashboard Access'
        ];
        return view('admin/unlock', $data);
    }

    public function process()
    {
        $password = $this->request->getPost('password');
        $expectedPassword = env('ADMIN_DASHBOARD_PASSWORD', 'expedient2026');

        if ($password === $expectedPassword) {
            session()->set('admin_unlocked', true);
            return redirect()->to('/admin/dashboard')->with('success', 'Akses Admin diberikan.');
        }

        return redirect()->back()->with('error', 'Sandi Akses tidak valid.');
    }
}
