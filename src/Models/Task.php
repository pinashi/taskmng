<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

/**
 * Model for managing tasks
 */
Class Task {
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
     * Get all tasks for a specific user.
     *
     * @param int $userId User ID
     * @return array List of tasks, empty array on failure
     */
    public function getAll(int $userId): array {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM tasks WHERE user_id = ?
            ');
            $stmt->execute([$userId]); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Get a single task by ID.
     *
     * @param int $id Task ID
     * @return array|false Task data or false if not found or on failure
     */
    public function getById(int $id): array|false {
        try {    
            $stmt = $this->db->prepare('
                SELECT * FROM tasks WHERE id = ?
            ');
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Create a new task.
     *
     * @param array $data Task data containing user_id, title, description, status, deadline, attachment
     * @return bool True on success, false on failure
     */
    public function create(array $data): bool {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO tasks (user_id, title, description, status, deadline, attachment) VALUES (?, ?, ?, ?, ?, ?)
            ');
            return $stmt->execute([
                $data['user_id'],
                $data['title'],
                $data['description'],
                $data['status'],
                $data['deadline'],
                $data['attachment'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing task.
     *
     * @param int $id Task ID
     * @param array $data Updated data containing title, description, status, deadline, attachment
     * @return bool True on success, false on failure
     */
    public function update(int $id, array $data): bool {
        try {
            $stmt = $this->db->prepare('
                UPDATE tasks SET title = ?, description = ?, status = ?, deadline = ?, attachment = ? WHERE id = ?        
            ');
            return $stmt->execute([
                $data['title'],
                $data['description'],
                $data['status'],
                $data['deadline'],
                $data['attachment'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Delete a task by ID.
     *
     * @param int $id Task ID
     * @return bool True on success, false on failure
     */
    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM tasks WHERE id = ?
            ');
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update task status.
     *
     * @param int $id Task ID
     * @param string $status New status: todo, in_progress, done
     * @return bool True on success, false on failure
     */
    public function updateStatus(int $id, string $status): bool {
        try {
            $stmt = $this->db->prepare('
                UPDATE tasks SET status = ? WHERE id = ?
            ');
            return $stmt->execute([
                $status,
                $id
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}