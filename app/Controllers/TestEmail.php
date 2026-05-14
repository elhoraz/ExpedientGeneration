<?php
namespace App\Controllers;

class TestEmail extends BaseController
{
    public function index()
    {
        $emailService = \Config\Services::email();
        $emailService->setFrom('elhorastudying@gmail.com', 'Expedient Generation');
        $emailService->setTo('elhorastudying@gmail.com');
        $emailService->setSubject('Test Email dari CI4');
        $emailService->setMessage('Ini adalah test email.');
        
        if ($emailService->send()) {
            echo "Email berhasil terkirim.\n";
        } else {
            echo "Email gagal:\n";
            echo $emailService->printDebugger(['headers', 'subject', 'body']);
        }
    }
}
