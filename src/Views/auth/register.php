<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>

    <?php if (isset($error)): ?>
        <p style="color: red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/register">
        <input type="text" name="name" placeholder="Имя" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Пароль" required><br>
        <button type="submit">Зарегистрироваться</button>
    </form>

    <a href="/login">Уже есть аккаунт?</a>
</body>
</html>