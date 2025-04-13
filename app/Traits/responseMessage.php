<?php

namespace App\Traits;

trait responseMessage
{
    public function successMessage(string $message, $data = null, $status = 200): array{
        return[
            'success' => true,
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ];
    }

    public function errorResponse(string $message, $status = 500): array{
        return[
            'success' => false,
            'status'  => 500,
            'message' => $message,
        ];
    }

    public function notFoundResponse(string $message): array{
        return $this->errorResponse($message, 404);
    }

}
