<?php

namespace App\Services;
use PHPUnit\Event\Code\Throwable;
use App\Models\Error;

class LoggerService
{
    public function logError(\Throwable $exception): void
    {
        Error::create([
            'error_message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
        \Log::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
    }

}
