<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Task;
use App\Traits\TaskAccessTrait;

Class TaskController {
    use TaskAccessTrait;

    private Task $task;

    public function __construct(Task $task) {
        $this->task = $task;
    }

    /**
     * Validate post form data.
     *
     * @param array $data Form data containing title and content
     * @return array List of validation errors, empty if valid
     */
    private function validate(array $data): array {
        $errors = [];
    
        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = 'Заголовок обязателен';
        }
    
        if (strlen($data['title'] ?? '') > 255) {
            $errors[] = 'Заголовок не должен превышать 255 символов';
        }
    
        return $errors;
    }   

    private function handleUpload(array $file): ?string {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== 0) {
            return null;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/pdf'];
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowed)) {
            return null;
        }

        if ($file['size'] > $maxSize) {
            return null;
        }

        $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename   = uniqid() . '.' . $extension;
        $uploadDir  = __DIR__ . '/../public/uploads/';

        if (!$uploadDir) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            error_log('Failed to move uploaded file');
            return null;
        }
    
        return $filename;
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        $tasks = $this->task->getAll($_SESSION['user_id']);
        require_once __DIR__ . '/../Views/tasks/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        $errors = [];
        $old = [];
        require_once __DIR__ . '/../Views/tasks/create.php';
        return;
    }

    public function store(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $old = $_POST;
            require_once __DIR__ . '/../Views/tasks/create.php';
            return;
        }

        $attachment = $this->handleUpload($_FILES['attachment'] ?? []);

        $this->task->create([
            'user_id'     => $_SESSION['user_id'],
            'title'       => trim($_POST['title']),
            'description' => trim($_POST['description'] ?? ''),
            'status'      => $_POST['status'],
            'deadline'    => $_POST['deadline'] ?: null,
            'attachment'  => $attachment
        ]);

        header('Location: /');
    }

    public function edit(int $id): void {
        $task = $this->task->getById($id);

        if (!$this->checkTaskAccess($task)) {
            return;
        }

        $errors = [];
        $old = [];

        require_once __DIR__ . '/../Views/tasks/edit.php';
    }

    public function update(int $id): void {
        $task = $this->task->getById($id);

        if (!$this->checkTaskAccess($task)) {
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $old = $_POST;
            require_once __DIR__ . '/../Views/tasks/edit.php';
            return;
        }

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
            $attachment = $this->handleUpload($_FILES['attachment']);
        } else {
            $attachment = $task['attachment'];
        }

        $data = [
            'title'         => $_POST['title'],
            'description'   => $_POST['description'],
            'status'        => $_POST['status'],
            'deadline'      => $_POST['deadline'] ?: null,
            'attachment'    => $attachment
        ];
    
        $this->task->update($id, $data);
        header('Location: /');
    }

    public function updateStatus(int $id): void {
        $task = $this->task->getById($id);

        if (!$this->checkTaskAccess($task)) {
            return;
        }

        $allowed = ['todo', 'in_progress', 'done'];
        $status  = $_POST['status'] ?? '';

        if (!in_array($status, $allowed)) {
            header('Location: /');
            return;
        }

        $task = $this->task->updateStatus($id, $status);
        header('Location: /');
    }

    public function destroy(int $id): void {
        $task = $this->task->getById($id);

        if (!$this->checkTaskAccess($task)) {
            return;
        }

        $this->task->delete($id);
        header('Location: /');
    }
}