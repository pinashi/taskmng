<?php

declare(strict_types=1);

namespace App\Traits;

trait TaskAccessTrait {
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