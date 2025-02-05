<?php
// Подключение к базе данных
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверка наличия куков для авторизации
$userName = "Неизвестно";  // Значение по умолчанию
if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_hash'])) {
    // Получаем значения из куки
    $userId = $_COOKIE['user_id'];
    $userHash = $_COOKIE['user_hash'];

    // Подготовленный запрос для безопасного получения данных из базы данных
    $query = $conn->prepare("SELECT user_hash, login FROM users WHERE id = ? LIMIT 1");
    $query->bind_param("i", $userId);  // Привязываем параметр для защиты от SQL инъекций
    $query->execute();
    $result = $query->get_result();
    
    // Проверка, есть ли пользователь в базе данных
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();  // Получаем данные пользователя из базы

        // Проверяем совпадение хеша из базы данных с хешем в куках
        if ($user['user_hash'] === $userHash) {
            $userName = $user['login'];  // Логин авторизованного пользователя
        }
    }
}

// Запись посещения текущей страницы в таблицу page_visits
$pageName = $_SERVER['PHP_SELF'];  // или используйте $_SERVER['REQUEST_URI'] для полного URL
$userIP = $_SERVER['REMOTE_ADDR']; // IP пользователя
$visitTime = date("Y-m-d H:i:s");  // Текущее время
$actionType = "view";  // Тип действия (например, просмотр страницы)

$stmt = $conn->prepare("INSERT INTO page_visits (user_ip, page_name, visit_time, action_type, user_name) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $userIP, $pageName, $visitTime, $actionType, $userName);
$stmt->execute();

// Закрытие соединения после записи
$stmt->close();

// Закрытие соединения с базой данных
$conn->close();
?>
