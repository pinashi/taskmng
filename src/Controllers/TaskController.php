<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Task;
use App\Traits\TaskAccessTrait;
use App\Validators\TaskValidator;

/**
 * Handles all task operations: listing, creating, editing, updating and deleting.
 * Uses TaskAccessTrait for access control checks.
 */
Class TaskController {
    use TaskAccessTrait;

    /**
     * @var Task $task Task model instance
     */
    private Task $task;
    /**
     * @var TaskValidator $validator Task validator instance
     */
    private TaskValidator $validator;

    /**
     * Initializes task model via dependency injection.
     *
     * @param Task $task Task model instance
     * @param TaskValidator $validator Task validator instance
     */
    public function __construct(Task $task, TaskValidator $validator) {
        $this->task = $task;
        $this->validator = $validator;
    }

    /**
     * Handle file upload.
     * Validates file type and size, saves file to uploads directory.
     *
     * @param array $file $_FILES array entry
     * @return string|null Filename on success, null if no file or on failure
     */
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

    /**
     * Display list of all tasks grouped by status.
     * Requires authentication.
     *
     * @return void
     */
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        $tasks = $this->task->getAll($_SESSION['user_id']);
        require_once __DIR__ . '/../Views/tasks/index.php';
    }

    /**
     * Display task creation form.
     * Requires authentication.
     *
     * @return void
     */
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

    /**
     * Handle task creation form submission.
     * Validates input, handles file upload and saves task.
     * Requires authentication.
     *
     * @return void
     */
    public function store(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        $errors = $this->validator->validate($_POST);

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

    /**
     * Display task edit form.
     * Requires authentication and task ownership.
     *
     * @param int $id Task ID
     * @return void
     */
    public function edit(int $id): void {
        $task = $this->task->getById($id);

        if (!$this->checkTaskAccess($task)) {
            return;
        }

        $errors = [];
        $old = [];

        require_once __DIR__ . '/../Views/tasks/edit.php';
    }

    /**
     * Handle task update form submission.
     * Validates input, handles file upload and updates task.
     * Requires authentication and task ownership.
     *
     * @param int $id Task ID
     * @return void
     */
    public function update(int $id): void {
        $task = $this->task->getById($id);

        if (!$this->checkTaskAccess($task)) {
            return;
        }

        $errors = $this->validator->validate($_POST);

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

    /**
     * Update task status.
     * Validates status value against allowed values.
     * Requires authentication and task ownership.
     *
     * @param int $id Task ID
     * @return void
     */
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

    /**
     * Delete a task and redirect to home page.
     * Requires authentication and task ownership.
     *
     * @param int $id Task ID
     * @return void
     */
    public function destroy(int $id): void {
        $task = $this->task->getById($id);

        if (!$this->checkTaskAccess($task)) {
            return;
        }

        $this->task->delete($id);
        header('Location: /');
    }
}