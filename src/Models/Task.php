<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

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

    public function getAll(int $userId): array {
        $stmt = $this->db->prepare('
            SELECT * FROM tasks WHERE user_id = ?
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare('
            SELECT * FROM tasks WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare('
            INSERT INTO tasks (user_id, title, description, status, deadline) VALUES (?, ?, ?, ?, ?)
        ');
        return $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['description'],
            $data['status'],
            $data['deadline']
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('
            UPDATE tasks SET title = ?, description = ?, status = ?, deadline = ? WHERE id = ?        
        ');
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['status'],
            $data['deadline'],
            $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('
            DELETE FROM tasks WHERE id = ?
        ');
        return $stmt->execute([$id]);
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare('
            UPDATE tasks SET status = ? WHERE id = ?
        ');
        return $stmt->execute([
            $status,
            $id
        ]);
    }
}