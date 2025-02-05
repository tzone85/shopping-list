<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database Class
 * 
 * Handles database connections and queries
 * 
 * @package App\Core
 */
class Database
{
    /**
     * @var PDO The database connection
     */
    private static ?PDO $instance = null;

    /**
     * Get database instance (Singleton Pattern)
     * 
     * @return PDO
     * @throws PDOException
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=utf8mb4",
                    $_ENV['DB_HOST'],
                    $_ENV['DB_DATABASE']
                );
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                self::$instance = new PDO(
                    $dsn,
                    $_ENV['DB_USERNAME'],
                    $_ENV['DB_PASSWORD'],
                    $options
                );
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$instance;
    }

    /**
     * Prevent direct creation of object
     */
    private function __construct()
    {
    }

    /**
     * Prevent cloning of instance
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserializing of instance
     */
    public function __wakeup()
    {
    }
}
