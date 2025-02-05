<?php
// Подключение к базе данных
// Подключение к базе данных
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";

// Создаем соединение с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Проверка наличия email в базе данных
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Генерация токена для сброса пароля
        $token = bin2hex(random_bytes(50));
        $expiry_time = time() + 3600; // 1 час для восстановления пароля

        // Сохранение токена и срока действия в базе данных
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        $stmt->bind_param("sis", $token, $expiry_time, $email);
        $stmt->execute();

        // Формирование ссылки для восстановления
        $reset_link = "http://site1.local/php/reset_password.php?token=" . $token;

        // Здесь можно либо вывести ссылку на экран, либо отправить на email
        echo "Ссылка для восстановления пароля: <a href=\"$reset_link\">$reset_link</a>";
    } else {
        echo "Email не найден.";
    }
} else {
    echo "Email не указан.";
}
?>
