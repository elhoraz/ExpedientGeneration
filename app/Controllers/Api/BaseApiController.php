<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

/**
 * BaseApiController
 * 
 * Base class untuk semua API controller.
 * Menyediakan helper method standar untuk response JSON yang konsisten.
 * 
 * Format response standar:
 * {
 *   "status": "success" | "error",
 *   "message": "...",
 *   "data": { ... }
 * }
 */
class BaseApiController extends BaseController
{
    /**
     * Return JSON response sukses.
     */
    protected function jsonSuccess($data = null, string $message = 'OK', int $code = 200)
    {
        return $this->response
            ->setStatusCode($code)
            ->setJSON([
                'status'  => 'success',
                'message' => $message,
                'data'    => $data
            ]);
    }

    /**
     * Return JSON response error.
     */
    protected function jsonError(string $message = 'Terjadi kesalahan.', int $code = 400, $errors = null)
    {
        $response = [
            'status'  => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $this->response
            ->setStatusCode($code)
            ->setJSON($response);
    }
}
