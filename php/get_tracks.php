<?php
// Настройки подключения к базе данных
$servername = "127.127.126.50";
$username = "root";
$password = ""; // Замените на ваш пароль
$dbname = "MusicBase"; // Замените на имя вашей базы данных

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем подключение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL-запрос для получения всех треков
$sql = "SELECT * FROM Track";
$result = $conn->query($sql);

// Проверяем, есть ли записи в базе данных
if ($result->num_rows > 0) {
    $tracks = array();

    // Считываем данные и добавляем их в массив
    while($row = $result->fetch_assoc()) {
        $tracks[] = $row;
    }

    // Отправляем данные в формате JSON
    echo json_encode($tracks);
} else {
    echo json_encode([]); // Если нет данных, отправляем пустой массив
}

// Закрываем подключение
$conn->close();
?>
