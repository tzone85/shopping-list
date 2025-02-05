<?php

namespace App\Services;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Throwable;

class LogService
{
    private Logger $logger;
    private static ?LogService $instance = null;

    private function __construct()
    {
        $this->logger = new Logger('mini-mvc');
        
        // Create logs directory if it doesn't exist
        if (!is_dir(dirname(__DIR__, 2) . '/logs')) {
            mkdir(dirname(__DIR__, 2) . '/logs', 0777, true);
        }

        // Add rotating file handler for errors (new file each day, keep 14 days of logs)
        $errorHandler = new RotatingFileHandler(
            dirname(__DIR__, 2) . '/logs/error.log',
            14,
            Logger::ERROR
        );

        // Add rotating file handler for all logs (new file each day, keep 7 days of logs)
        $allHandler = new RotatingFileHandler(
            dirname(__DIR__, 2) . '/logs/app.log',
            7,
            Logger::DEBUG
        );

        // Custom format to include datetime, level, message, and context
        $formatter = new LineFormatter(
            "[%datetime%] %level_name%: %message% %context% %extra%\n",
            "Y-m-d H:i:s"
        );

        $errorHandler->setFormatter($formatter);
        $allHandler->setFormatter($formatter);

        $this->logger->pushHandler($errorHandler);
        $this->logger->pushHandler($allHandler);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function error(string $message, array $context = [], ?Throwable $exception = null): void
    {
        if ($exception) {
            $context['exception'] = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }
        $this->logger->error($message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }
}
