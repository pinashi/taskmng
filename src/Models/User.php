<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

/**
 * Model for managing users.
 */
Class User {
    /**
     * @var PDO $db Database connection instance
     */
    private PDO $db;

    /**
     * Initializes database connection.
     */
    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Find a user by email address.
     *
     * @param string $email User email
     * @return array|false User data or false if not found
     */
    public function getByEmail(string $email): array|false {
        try {    
            $stmt = $this->db->prepare('
                SELECT * FROM users WHERE email = ?
            ');
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Create a new user with hashed password.
     *
     * @param array $data User data containing name, email, password
     * @return bool True on success, false on failure
     */
    public function create(array $data): bool {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO users (name, email, password) VALUES (?, ?, ?)
            ');
            return $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}