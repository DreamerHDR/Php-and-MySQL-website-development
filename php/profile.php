<?php
session_start();
// Проверяем, авторизован ли пользователь
$authorized = false;
include('track_visit.php');
// Подключение к базе данных
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";

// Создаем соединение с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверка наличия куков для авторизации
if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_hash'])) {
    $userId = $_COOKIE['user_id'];
    $userHash = $_COOKIE['user_hash'];

    // Подготовленный запрос для безопасного получения данных из базы данных
    $query = $conn->prepare("SELECT id, login, email, fullname, avatar, user_hash FROM users WHERE id = ? LIMIT 1");
    $query->bind_param("i", $userId);
    $query->execute();
    $result = $query->get_result();

    // Проверка, есть ли пользователь в базе данных
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['user_hash'] === $userHash) {
            $authorized = true;
        } else {
            echo "Хеши не совпадают, вы не авторизованы.";
        }
    } else {
        echo "Пользователь не найден.";
    }
} else {
    echo "Вы не авторизованы!";
}

// Функция для изменения размера изображения
function resizeImage($file, $width, $height) {
    list($originalWidth, $originalHeight) = getimagesize($file);
    $src = imagecreatefromstring(file_get_contents($file));

    // Создаем пустое изображение с новыми размерами
    $dst = imagecreatetruecolor($width, $height);

    // Копируем и изменяем размеры оригинального изображения
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

    return $dst;
}

// Если форма отправлена, обновляем данные
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $authorized) {
    $newEmail = $_POST['email'];
    $newFullname = $_POST['fullname'];
    $newPassword = $_POST['password'];

    // Обновление email и полного имени
    $updateQuery = $conn->prepare("UPDATE users SET email = ?, fullname = ? WHERE id = ?");
    $updateQuery->bind_param("ssi", $newEmail, $newFullname, $userId);
    $updateQuery->execute();

    // Обновление пароля, если он не пустой
    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePasswordQuery = $conn->prepare("UPDATE users SET pass = ? WHERE id = ?");
        $updatePasswordQuery->bind_param("si", $hashedPassword, $userId);
        $updatePasswordQuery->execute();
    }

    // Обработка загрузки аватара
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatarName = $_FILES['avatar']['name'];
        $avatarTmpName = $_FILES['avatar']['tmp_name'];
        $avatarFileName = 'avatar_' . $userId . '.' . pathinfo($avatarName, PATHINFO_EXTENSION);
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/avatars/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . $avatarFileName;

        // Изменяем размер изображения перед сохранением
        $resizedAvatar = resizeImage($avatarTmpName, 100, 100); // Устанавливаем новый размер, например, 100x100

        // Сохраняем уменьшенное изображение в формате JPEG
        if (imagejpeg($resizedAvatar, $uploadPath)) {
            imagedestroy($resizedAvatar); // Освобождаем память
            $updateAvatarQuery = $conn->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $updateAvatarQuery->bind_param("si", $avatarFileName, $userId);
            $updateAvatarQuery->execute();
        } else {
            echo "Ошибка при сохранении уменьшенного изображения.";
        }
    }

    // Обновляем данные пользователя после изменения
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Профиль пользователя</title>
</head>
<body>
    <?php if ($authorized): ?>
        <div class="profile-container">
            <h1>Добро пожаловать, <?php echo htmlspecialchars($user['login']); ?>!</h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

            <form method="POST" enctype="multipart/form-data">
                <p><strong>Полное имя:</strong> 
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" /></p>

                <p><strong>Новый email:</strong> 
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required /></p>

                <p><strong>Новый пароль:</strong> 
                <input type="password" name="password" placeholder="Введите новый пароль" /></p>

                <p><strong>Аватар:</strong>
                <input type="file" name="avatar" accept="image/*" /></p>

                <button type="submit">Обновить данные</button>
            </form>

            <!-- Отображение аватара -->
            <?php if ($user['avatar']): ?>
                <img src="/uploads/avatars/<?php echo htmlspecialchars($user['avatar']) . '?' . time(); ?>" alt="Аватар" />
            <?php else: ?>
                <p>Аватар не установлен.</p>
            <?php endif; ?>

            <form action="../index.php" method="get">
                <button type="submit">Назад</button>
            </form>

            <a href="logout.php">Выход</a>
        </div>
    <?php else: ?>
        <p>Вы не авторизованы, пожалуйста, войдите в систему.</p>
    <?php endif; ?>
</body>
</html>
