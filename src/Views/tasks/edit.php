<?php
/** @var array $task */
/** @var array $errors */
/** @var array $old */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать задачу</title>
</head>
<body>
    <nav>
        <a href="/">← Назад</a>
        <a href="/logout">Выйти</a>
    </nav>

    <h1>Редактировать задачу</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/task/<?= $task['id'] ?>/edit" enctype="multipart/form-data">
        <label>Заголовок</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? $task['title']) ?>" required><br><br>

        <label>Описание</label><br>
        <textarea name="description" rows="5"><?= htmlspecialchars($old['description'] ?? $task['description'] ?? '') ?></textarea><br><br>

        <label>Статус</label><br>
        <select name="status">
            <?php
            $statuses = ['todo' => 'К выполнению', 'in_progress' => 'В процессе', 'done' => 'Выполнено'];
            $current  = $old['status'] ?? $task['status'];
            foreach ($statuses as $value => $label):
            ?>
                <option value="<?= $value ?>" <?= $current === $value ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Дедлайн</label><br>
        <input type="date" name="deadline" value="<?= $old['deadline'] ?? $task['deadline'] ?? '' ?>"><br><br>

        <label>Прикрепить файл</label><br>
        <?php if ($task['attachment']): ?>
            <p>Текущий файл: <a href="/uploads/<?= htmlspecialchars($task['attachment']) ?>" target="_blank">открыть</a></p>
        <?php endif; ?>
        <input type="file" name="attachment"><br><br>

        <button type="submit">Сохранить</button>
    </form>

    <form method="POST" action="/task/<?= $task['id'] ?>/delete">
        <button type="submit" onclick="return confirm('Удалить задачу?')">Удалить</button>
    </form>
</body>
</html>