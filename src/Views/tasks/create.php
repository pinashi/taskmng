<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая задача</title>
</head>
<body>
    <nav>
        <a href="/">← Назад</a>
        <a href="/logout">Выйти</a>
    </nav>

    <h1>Новая задача</h1>

    <?php if (!empty($errors ?? [])): ?>
        <ul style="color: red">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/task/create">
        <label>Заголовок</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>" required><br><br>

        <label>Описание</label><br>
        <textarea name="description" rows="5"><?= htmlspecialchars($old['description'] ?? '') ?></textarea><br><br>

        <label>Статус</label><br>
        <select name="status">
            <option value="todo">К выполнению</option>
            <option value="in_progress">В процессе</option>
            <option value="done">Выполнено</option>
        </select><br><br>

        <label>Дедлайн</label><br>
        <input type="date" name="deadline" value="<?= $old['deadline'] ?? '' ?>"><br><br>

        <button type="submit">Создать</button>
    </form>
</body>
</html>