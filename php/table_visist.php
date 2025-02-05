<?php
session_start();
// Подключение к базе данных
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверка наличия куков
if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
    $id = $_COOKIE['id'];
    $hash = $_COOKIE['hash'];

    // Проверяем, существует ли пользователь с таким id и hash
    $stmt = $conn->prepare("SELECT login FROM Users WHERE id = ? AND user_hash = ? LIMIT 1");
    $stmt->bind_param("is", $id, $hash);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Получаем логин пользователя
        $user = $result->fetch_assoc();
        $userName = $user['login']; // Логин авторизованного пользователя
    } else {
        $userName = "Неизвестно"; // Если пользователь не найден
    }
} else {
    $userName = "Неизвестно"; // Если куки отсутствуют (пользователь не авторизован)
}

// Запись посещения текущей страницы в таблицу page_visits
$pageName = $_SERVER['PHP_SELF'];  // URL
$userIP = $_SERVER['REMOTE_ADDR']; // IP пользователя
$visitTime = date("Y-m-d H:i:s");  // Текущее время
$actionType = "view";  // Тип действия

$stmt = $conn->prepare("INSERT INTO page_visits (user_ip, page_name, visit_time, action_type, user_name) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $userIP, $pageName, $visitTime, $actionType, $userName);
$stmt->execute();

// Получаем данные о посещениях с информацией о пользователях
$sql = "SELECT pv.id, pv.user_ip, pv.page_name, pv.visit_time, pv.action_type, u.login AS user_name
            FROM page_visits pv
            LEFT JOIN users u ON pv.user_name = u.login
            ORDER BY pv.visit_time DESC"; // Сортировка по времени посещения
$result = $conn->query($sql);

// Получаем данные о посещениях по датам для диаграммы
$sql_dates = "SELECT DATE(visit_time) AS visit_date, COUNT(*) AS visit_count
              FROM page_visits
              GROUP BY DATE(visit_time)
              ORDER BY visit_date DESC";
$result_dates = $conn->query($sql_dates);
$dates_data = [];
$counts_data = [];

while ($row = $result_dates->fetch_assoc()) {
    $dates_data[] = $row['visit_date'];
    $counts_data[] = $row['visit_count'];
}

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика посещаемости</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Статистика посещаемости</h1>
    <p><strong>Авторизованный пользователь:</strong> <?= htmlspecialchars($userName) ?></p>

    <h2>Данные о посещениях</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>IP пользователя</th>
                <th>Страница</th>
                <th>Время посещения</th>
                <th>Тип действия</th>
                <th>Имя пользователя</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['user_ip'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['page_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['visit_time'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['action_type'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['user_name'] ?? 'Неизвестно') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Нет данных для отображения.</p>
    <?php endif; ?>

    <!-- Диаграмма -->
    <h2>Статистика посещений по датам</h2>
    <canvas id="visitsChart" width="400" height="200"></canvas>
    <script>
        // Данные для диаграммы
        var dates = <?php echo json_encode([ 'dates' => $dates_data, 'counts' => $counts_data ]); ?>;

        var ctx = document.getElementById('visitsChart').getContext('2d');
        var visitsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates.dates,
                datasets: [{
                    label: 'Количество посещений',
                    data: dates.counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
