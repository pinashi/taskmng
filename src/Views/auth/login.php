<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body>
    <h1>Вход</h1>

    <?php if (isset($error)): ?>
        <p style="color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/login">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <button type="submit">Войти</button>
    </form>

    <a href="/register">Нет аккаунта?</a>
</body>
</html>