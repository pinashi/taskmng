<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Provides task access control methods for controllers.
 * Checks authentication and task ownership.
 */
trait TaskAccessTrait {
    /**
     * Check if current user has access to the task.
     * Redirects to login if not authenticated.
     * Returns 404 if task not found.
     * Returns 403 if user is not the task owner.
     *
     * @param array|false $task Task data or false if not found
     * @return bool True if access granted, false otherwise
     */
    private function checkTaskAccess(array|false $task): bool {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return false;
        }

        if (!$task) {
            http_response_code(404);
            require_once __DIR__ . '/../Views/404.php';
            return false;
        }

        if ((int)$task['user_id'] !== $_SESSION['user_id']) {
            http_response_code(403);
            echo 'Нет доступа';
            return false;
        }

        return true;
    }
}