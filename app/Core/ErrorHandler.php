<?php

namespace App\Core;

use App\Services\LogService;

/**
 * Error and Exception Handler
 * 
 * @package App\Core
 */
class ErrorHandler
{
    /**
     * @var LogService
     */
    private LogService $logger;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logger = LogService::getInstance();
    }

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     *
     * @return void
     * @throws \ErrorException
     */
    public function errorHandler(int $level, string $message, string $file, int $line): void
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param \Throwable $exception The exception
     *
     * @return void
     */
    public function exceptionHandler(\Throwable $exception): void
    {
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        // Log the exception with full context
        $this->logger->error(
            'Uncaught ' . get_class($exception) . ': ' . $exception->getMessage(),
            [
                'code' => $code,
                'url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ],
            $exception
        );

        if ($_ENV['APP_DEBUG'] === 'true') {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            if ($code == 404) {
                echo "<h1>Page not found</h1>";
            } else {
                echo "<h1>An error occurred</h1>";
                echo "<p>Please try again later. The error has been logged.</p>";
            }
        }
    }
}
