	<?php
	// Страница авторизации
	session_start();  // Старт сессии для сохранения ошибок и данных
	include('track_visit.php');
	function generateCode($length = 10) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$code = "";
			$clen = strlen($chars) - 1;  
			while (strlen($code) < $length) {
					$code .= $chars[mt_rand(0, $clen)];  
			}
			return $code;
	}

	// Соединение с БД
	$servername = "127.127.126.50";
	$username = "root";
	$password = "";
	$dbname = "MusicBase";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
	}

	if (isset($_POST['submit'])) {
			// Подготовленный запрос
			$stmt = $conn->prepare("SELECT id, pass FROM Users WHERE login = ? LIMIT 1");
			$stmt->bind_param("s", $_POST['login']);
			$stmt->execute();
			$result = $stmt->get_result();
			$data = $result->fetch_assoc();

			if ($data) {
					// Если данные найдены, сравниваем пароли
					if ($data['pass'] === md5(md5($_POST['pass']))) {
							// Генерация хеша
							$hash = md5(generateCode(10));
							
							$insip = "";
							if (!isset($_POST['not_attach_ip'])) {
									// Если пользователя выбрал привязку к IP
									$insip = ", user_ip = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')";
							}
							
							// Обновляем хеш авторизации и IP в БД
							$query = "UPDATE Users SET user_hash = '" . $hash . "'" . $insip . " WHERE id = '" . $data['id'] . "'";
							if (!mysqli_query($conn, $query)) {
									die("Error executing query: " . mysqli_error($conn));
							}

							// Устанавливаем куки
							setcookie("id", $data['id'], time() + 60 * 60 * 24 * 30);
							setcookie("hash", $hash, time() + 60 * 60 * 24 * 30);

							// Переадресация
							header("Location: check.php");
							exit();
					} else {
							print "Вы ввели неправильный логин/пароль";
					}
			} else {
					// Если пользователь не найден в базе данных
					print "Пользователь с таким логином не найден";
			}
	}

	?>