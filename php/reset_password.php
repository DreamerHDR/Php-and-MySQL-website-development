<?php
// Подключение к базе данных
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";

// Создаем соединение с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Проверка токена
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > ?");
    $current_time = time();
    $stmt->bind_param("si", $token, $current_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = md5(md5(trim($_POST['new_password'])));

            // Обновление пароля и удаление токена
            $stmt = $conn->prepare("UPDATE users SET pass = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $new_password, $token);
            $stmt->execute();

            echo "Пароль успешно изменен.";
						header("Location: ../index.php");
        } else {
            // Форма для ввода нового пароля
            echo '
                <form action="" method="POST">
                    <label for="new_password">Введите новый пароль:</label>
                    <input type="pass" id="new_password" name="new_password" required>
                    <button type="submit">Сменить пароль</button>
                </form>
            ';
        }
    } else {
        echo "Ссылка для сброса пароля недействительна или истекла.";
    }
} else {
    echo "Токен не передан.";
}
?>
