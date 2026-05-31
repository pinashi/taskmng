<?php
/** @var array $tasks */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои задачи</title>
</head>
<body>
    <nav>
        <?php if (isset($_SESSION['user_name'])): ?>
            <span>Привет, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="/task/create">Создать задачу</a>
            <a href="/logout">Выйти</a>
        <?php else: ?>
            <a href="/login">Войти</a>
            <a href="/register">Регистрация</a>
        <?php endif; ?>
    </nav>

    <h1>Мои задачи</h1>

    <?php if (empty($tasks)): ?>
        <p>Задач пока нет. <a href="/task/create">Создать первую?</a></p>
    <?php else: ?>

        <?php
        $groups = [
            'todo'        => 'К выполнению',
            'in_progress' => 'В процессе',
            'done'        => 'Выполнено'
        ];
        ?>

        <?php foreach ($groups as $status => $label): ?>
            <?php $filtered = array_filter($tasks, fn($t) => $t['status'] === $status); ?>
            <?php if (!empty($filtered)): ?>
                <h2><?= $label ?></h2>
                <?php foreach ($filtered as $task): ?>
                    <div>
                        <strong><?= htmlspecialchars($task['title']) ?></strong>
                        <?php if ($task['description']): ?>
                            <p><?= htmlspecialchars($task['description']) ?></p>
                        <?php endif; ?>
                        <?php if ($task['deadline']): ?>
                            <small>Дедлайн: <?= $task['deadline'] ?></small>
                        <?php endif; ?>

                        <form method="POST" action="/task/<?= $task['id'] ?>/status" style="display:inline">
                            <select name="status" onchange="this.form.submit()">
                                <option value="todo"        <?= $task['status'] === 'todo'        ? 'selected' : '' ?>>К выполнению</option>
                                <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>В процессе</option>
                                <option value="done"        <?= $task['status'] === 'done'        ? 'selected' : '' ?>>Выполнено</option>
                            </select>
                        </form>

                        <a href="/task/<?= $task['id'] ?>/edit">Редактировать</a>

                        <form method="POST" action="/task/<?= $task['id'] ?>/delete" style="display:inline">
                            <button type="submit" onclick="return confirm('Удалить задачу?')">Удалить</button>
                        </form>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>

    <?php endif; ?>
</body>
</html>