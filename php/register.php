<?php
session_start();  // Старт сессии для сохранения ошибок и данных
include('track_visit.php');
// Соединяемся с базой данных
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";
	
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['submit'])) {
    $err = array();

    // Проверяем логин
    if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }
    if(strlen($_POST['login']) < 3 || strlen($_POST['login']) > 30) {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // Проверяем, существует ли уже пользователь с таким логином
    $query = mysqli_query($conn, "SELECT COUNT(id) FROM users WHERE login='" . mysqli_real_escape_string($conn, $_POST['login']) . "'");
    $result = mysqli_fetch_row($query);
    if($result[0] > 0) {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Проверяем, что email корректный
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $err[] = "Некорректный email";
    }

    // Проверяем, что пароли совпадают
    if ($_POST['password'] !== $_POST['confirm-password']) {
        $err[] = "Пароли не совпадают";
    }

    // Проверяем пароль (добавляем минимальные требования к паролю)
    if(strlen($_POST['password']) < 6) {
        $err[] = "Пароль должен быть не менее 6 символов";
    }

    // Если нет ошибок, добавляем нового пользователя
    if(count($err) == 0) {
        $login = $_POST['login'];
        $email = $_POST['email'];

        // Шифруем пароль
        $password = md5(md5(trim($_POST['password'])));

        // Генерируем user_hash для безопасности
        $user_hash = md5(uniqid(rand(), true));

                // Вставляем данные в базу
								$query = "INSERT INTO users (login, pass, email, user_hash, user_ip) 
								VALUES ('$login', '$password', '$email', '$user_hash', '$user_ip')";

			if(mysqli_query($conn, $query)) {
					// После успешной регистрации перенаправляем на страницу логина
					$_SESSION['success'] = "Вы успешно зарегистрировались!";
					header("Location: ../index.php");
					exit();
			} else {
					echo "Ошибка при регистрации: " . mysqli_error($conn);
			}
	} else {
			// Сохраняем ошибки в сессии
			$_SESSION['errors'] = $err;
	}
}

// Проверяем, если есть ошибки в сессии, выводим их
if (isset($_SESSION['errors'])) {
	echo "<b>При регистрации произошли следующие ошибки:</b><br>";
	foreach($_SESSION['errors'] as $error) {
			echo $error . "<br>";
	}
	unset($_SESSION['errors']);  // Очищаем ошибки после их отображения
}
?>

