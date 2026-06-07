<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\TaskController;
use App\Controllers\AuthController;
use App\Models\Task;
use App\Models\User;
use App\Container;
use App\Router;
use App\Validators\TaskValidator;

$container = new Container();

$container->bind(TaskController::class, function($container) {
    return new TaskController(
        $container->make(Task::class),
        new TaskValidator()
    );
});

$container->bind(AuthController::class, function($container) {
    return new AuthController(
        $container->make(User::class)
    );
});

$router = new Router($container);

$router->get('login',       [AuthController::class, 'login']);
$router->post('login',      [AuthController::class, 'loginStore']);
$router->get('register',    [AuthController::class, 'register']);
$router->post('register',   [AuthController::class, 'registerStore']);
$router->get('logout',      [AuthController::class, 'logout']);

$router->get('',                    [TaskController::class, 'index']);
$router->get('task/create',         [TaskController::class, 'create']);
$router->post('task/create',        [TaskController::class, 'store']);
$router->get('task/{id}/edit',      [TaskController::class, 'edit']);
$router->post('task/{id}/edit',     [TaskController::class, 'update']);
$router->post('task/{id}/delete',   [TaskController::class, 'destroy']);
$router->post('task/{id}/status',   [TaskController::class, 'updateStatus']);

$router->dispatch();