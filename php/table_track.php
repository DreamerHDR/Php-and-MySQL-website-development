<?php
session_start();
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

// Обработка удаления записи
if (isset($_GET['delete'])) {
    $trackIdToDelete = $_GET['delete'];
    $deleteQuery = "DELETE FROM Track WHERE track_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $trackIdToDelete);
    $deleteStmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка редактирования записи
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $trackIdToEdit = $_POST['track_id'];
    $trackName = $_POST['trackName'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $releaseDate = $_POST['releaseDate'];
    $imageFileName = null;

    // Обработка загрузки нового изображения
    if (isset($_FILES['newImage']) && $_FILES['newImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['newImage']['tmp_name'];
        $imageName = $_FILES['newImage']['name'];
        $imageFileName = 'image_' . time() . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . $imageFileName;
        if (move_uploaded_file($imageTmpName, $uploadPath)) {
            // Успешная загрузка
        } else {
            $imageFileName = null; // Если ошибка загрузки
        }
    }

    $updateQuery = "UPDATE Track SET trackName = ?, category = ?, price = ?, releaseDate = ?, image = ? WHERE track_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssssi", $trackName, $category, $price, $releaseDate, $imageFileName, $trackIdToEdit);
    $updateStmt->execute();
}

// Обработка добавления новой записи
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $trackName = $_POST['newTrackName'];
    $category = $_POST['newCategory'];
    $price = $_POST['newPrice'];
    $releaseDate = $_POST['newReleaseDate'];
    $imageFileName = null; // По умолчанию нет изображения

    // Обработка загрузки изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageFileName = 'image_' . time() . '.' . $imageExtension;
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadPath = $uploadDir . $imageFileName;
        if (move_uploaded_file($imageTmpName, $uploadPath)) {
            // Успешная загрузка
        } else {
            $imageFileName = null; // Если ошибка загрузки
        }
    }

    // Вставка нового трека в базу данных
    $insertQuery = "INSERT INTO Track (trackName, category, price, releaseDate, image) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("sssss", $trackName, $category, $price, $releaseDate, $imageFileName);
    $insertStmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Извлечение данных о треках из базы данных
$query = "SELECT track_id, trackName, category, price, releaseDate, image FROM Track";
$result = $conn->query($query);

// Проверка, есть ли данные
if ($result->num_rows > 0):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Треки</title>
</head>
<body>
    <h1>Треки</h1>

    <!-- Таблица с данными о треках -->
    <table>
        <thead>
            <tr>
                <th>Track ID</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Дата релиза (Год)</th>
                <th>Изображение</th>
                <th>Редактировать</th>
                <th>Удалить</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($track = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($track['track_id']); ?></td>
                <td><?php echo htmlspecialchars($track['trackName']); ?></td>
                <td><?php echo htmlspecialchars($track['category']); ?></td>
                <td><?php echo htmlspecialchars($track['price']); ?></td>
                <td><?php echo htmlspecialchars(date("Y", strtotime($track['releaseDate']))); ?></td>
                <td>
                    <?php if ($track['image']): ?>
                        <img src="/uploads/images/<?php echo htmlspecialchars($track['image']); ?>" alt="Изображение" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <p>Изображение трека не установлено.</p>
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Форма для редактирования -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="track_id" value="<?php echo $track['track_id']; ?>" />
                        <input type="text" name="trackName" value="<?php echo htmlspecialchars($track['trackName']); ?>" required />
                        <input type="text" name="category" value="<?php echo htmlspecialchars($track['category']); ?>" required />
                        <input type="number" name="price" value="<?php echo htmlspecialchars($track['price']); ?>" required />
                        <input type="text" name="releaseDate" value="<?php echo htmlspecialchars($track['releaseDate']); ?>" required />
                        <input type="file" name="newImage" />
                        <button type="submit" name="edit">Редактировать</button>
                    </form>
                </td>
                <td>
                    <!-- Кнопка для удаления -->
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete=<?php echo $track['track_id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот трек?')">Удалить</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Форма для добавления нового трека -->
    <h2>Добавить новый трек</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <input type="text" name="newTrackName" placeholder="Название трека" required />
        <input type="text" name="newCategory" placeholder="Категория" required />
        <input type="number" name="newPrice" placeholder="Цена" required />
        <input type="text" name="newReleaseDate" placeholder="Дата релиза (Год)" required />
        <input type="file" name="image" required />
        <button type="submit" name="add">Добавить</button>
    </form>
		<form action="../index.php" method="get">
                <button type="submit">Назад</button>
            </form>

</body>
</html>
<?php
else:
    echo "Нет данных о треках.";
endif;

// Закрытие соединения с базой данных
$conn->close();
?>
