<?php
session_start();
$authorized = false; // Изначально пользователь не авторизован
$isAdmin = false; // Флаг для проверки, является ли пользователь администратором
include('php/track_visit.php');
// Подключение к базе данных
$servername = "127.127.126.50";  // Адрес сервера базы данных
$username = "root";              // Имя пользователя
$password = "";                  // Пароль
$dbname = "MusicBase";           // Имя базы данных

// Создаем соединение с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверка наличия куков для авторизации
if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_hash'])) {
    // Получаем значения из куки
    $userId = $_COOKIE['user_id'];
    $userHash = $_COOKIE['user_hash'];

    // Подготовленный запрос для безопасного получения данных из базы данных
    $query = $conn->prepare("SELECT user_hash, root FROM users WHERE id = ? LIMIT 1");
    $query->bind_param("i", $userId);  // Привязываем параметр для защиты от SQL инъекций
    $query->execute();
    $result = $query->get_result();
    
    // Проверка, есть ли пользователь в базе данных
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();  // Получаем данные пользователя из базы

        // Проверяем совпадение хеша из базы данных с хешем в куках
        if ($user['user_hash'] === $userHash) {
            $authorized = true;  // Устанавливаем флаг авторизации в true
            // Проверяем, является ли пользователь администратором
            if ($user['root'] == 1) {
                $isAdmin = true;  // Устанавливаем флаг для администратора
            }
        }
    }
}

// Закрываем соединение с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_gallery.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Gallery</title>
</head>
<body class="header-collapse">
	
    <div id="site-content">
        <header class="site-header">
            <div class="container_header">
                <a href="index.php" id="branding">
                    <img src="images/4.png" alt="Site Title">
                </a>

                <nav class="main-navigation">
                    <ul class="menu">
                        <li class="menu-item current-menu-item"><a href="index.php">Главная</a></li>
                        <li class="menu-item"><a href="about.php">О компании</a></li>
                        <li class="menu-item"><a href="gallery.php">Каталог</a></li>
                        <li class="menu-item"><a href="contact.php">Контакты</a></li>
                    </ul>
                </nav>
            </div>
        </header>


				<div class="wrapper">
					<div id="search-container">
							<input
									type="search"
									id="search-input"
									placeholder="Введите название трека..."
							/>
							<button id="search">Найти</button>
					</div>
					<div id="buttons">
							<button class="button-value" onclick="filterProduct('Все треки')">Все треки</button>
							<button class="button-value" onclick="filterProduct('Lil Peep')">Lil Peep</button>
							<button class="button-value" onclick="filterProduct('6ix9ine')">6ix9ine</button>
							<button class="button-value" onclick="filterProduct('Big Baby Tape')">Big Baby Tape</button>
							<button class="button-value" onclick="filterProduct('Face')">Face</button>
					</div>
			
					<!-- Кнопка фильтра под другими кнопками -->
					<button id="filter-toggle" class="button-value filter-button">Фильтр</button>
			
					<div id="filter-menu" class="hidden">
							<label for="price-min">Цена от:</label>
							<input type="number" id="price-min" placeholder="Минимум" />
							<label for="price-max">до:</label>
							<input type="number" id="price-max" placeholder="Максимум" />
							<div>
								<label for="release-date-min">Минимальная дата:</label>
								<input type="range" id="release-date-min" min="2010" max="2024" value="2010" step="1">
								<span id="release-date-min-value">2010</span>
						</div>
				
						<div>
								<label for="release-date-max">Максимальная дата:</label>
								<input type="range" id="release-date-max" min="2010" max="2024" value="2024" step="1">
								<span id="release-date-max-value">2024</span>
						</div>
						<div>
							<label> Выбрать всех</label><br />
							<div>
									<label><input type="checkbox" id="select-all" />  Lil Peep</label>
							</div>
							<div class="artist-checkboxes">
									<label><input type="checkbox" value="Lil Peep" class="artist-checkbox" />6ix9ine</label><br />
									<label><input type="checkbox" value="6ix9ine" class="artist-checkbox" />Big Baby Tape</label><br />
									<label><input type="checkbox" value="Big Baby Tape" class="artist-checkbox" /> Face</label><br />
									<label><input type="checkbox" value="Face" class="artist-checkbox" /></label><br />
							</div>
					</div>
							<button onclick="applyFilters()">Применить</button>
					</div>
			
					<div id="products"></div>
			</div>
							
				


				<footer class="site-footer">
					<div class="container">
						<img src="images/2.png" alt="Site Name">
						<address>
							<p>Ленинградский просп., 80, корп. 17, Москва<br><a href="tel:8(950) 697 87 55">8(950)-697-87-55</a> <br> <a href="morozov95080@gmail.com">morozov95080@gmail.com</a></p> 
						</address> 
						<form action="#" class="newsletter-form">
							<input type="email" placeholder="Введите почту...">
							<input type="submit" class="button cut-corner" value="Subscribe">
						</form>
						<div class="site-footer">
							<div class="social-links">
									<a href="https://vk.com/vlad_morozov16" target="_blank" class="fab fa-vk"></a>
									<a href="https://t.me/Dreamerokk" target="_blank" class="fab fa-telegram"></a>
									<a href="https://github.com/DreamerHDR" target="_blank" class="fab fa-github"></a>
									<a href="mailto:morozov95080@gmail.com" target="_blank" class="fa fa-envelope"></a>
							</div>
					</div>
					
						<p class="copy">Copyright 2024 Company Name. Designed by Vladislav Morozov. All right reserved</p>
					</div>
				</footer>
			
    </div>
		<script src="js/script_gallery.js"></script>
</body>
</html>