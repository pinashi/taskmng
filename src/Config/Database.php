<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

/**
 * Database connection manager using Singleton pattern.
 * Ensures only one PDO connection is created per request.
 */
Class Database {
    /**
     * @var PDO|null Single database connection instance
     */
    private static ?PDO $connection = null;

    /**
     * Private constructor to prevent direct instantiation.
     * Use getConnection() to get the database connection.
     */
    private function __construct() {}
    
    /**
     * Get the database connection instance.
     * Creates a new connection if one does not exist.
     *
     * @return PDO Active database connection
     * @throws PDOException If connection fails
     */
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                $env = parse_ini_file(__DIR__ . '/../.env');

                self::$connection = new PDO(
                    "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8",
                    $env['DB_USER'],
                    $env['DB_PASSWORD'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                error_log($e->getMessage());
                die('Database connection failed');
            }
        }
    
        return self::$connection;
    }
}