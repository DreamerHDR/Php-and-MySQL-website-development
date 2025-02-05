<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Восстановление пароля</title>
</head>
<body>
    <h2>Восстановление пароля</h2>
    <form action="send_reset_link.php" method="POST">
        <label for="email">Введите ваш email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Отправить ссылку для восстановления</button>
    </form>
</body>
</html>
