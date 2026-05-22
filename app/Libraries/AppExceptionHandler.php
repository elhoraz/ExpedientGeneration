<?php

namespace App\Libraries;

use CodeIgniter\Debug\ExceptionHandler;
use Throwable;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ActivityLogger;

class AppExceptionHandler implements \CodeIgniter\Debug\ExceptionHandlerInterface
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function handle(
        Throwable $exception,
        RequestInterface $request,
        ResponseInterface $response,
        int $statusCode,
        int $exitCode
    ): void {
        // Log error untuk tracking (hindari 404)
        if ($statusCode !== 404) {
            try {
                $userId = session()->get('user_id') ?? 0;
                $msg = "[$statusCode] " . $exception->getMessage() . " at " . $exception->getFile() . ":" . $exception->getLine();
                ActivityLogger::log('SYSTEM_ERROR', $msg, $userId);
            } catch (\Exception $e) {
                // Jangan sampai logger melempar exception lagi (infinite loop)
            }
        }

        // Jalankan default handler CI4 untuk render halaman error (Composition/Delegate)
        $defaultHandler = new \CodeIgniter\Debug\ExceptionHandler($this->config);
        $defaultHandler->handle($exception, $request, $response, $statusCode, $exitCode);
    }
}
