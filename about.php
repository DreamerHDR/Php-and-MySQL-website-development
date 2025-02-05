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

    <link id="theme-stylesheet" rel="stylesheet" href="css/style_about.css">
    <link rel="stylesheet" href="css/style_about_print.css" media="print">
		<script src="js/jquery-3.7.1.min.js"></script>
		<script src="js/script.js"></script>
    <title>About Us</title>
</head>
<body class="header-collapse">
	
    <div id="site-content">
        <header class="site-header">
            <div class="container">
                <a href="index.php" id="branding">
                    <img src="images/4.png" alt="Site Title">
                </a>

                <nav class="main-navigation">
                    <ul class="menu">
                        <li class="menu-item"><a href="index.php">Главная</a></li>
                        <li class="menu-item current-menu-item"><a href="about.php">О компании</a></li>
                        <li class="menu-item"><a href="gallery.php">Каталог</a></li>
                        <li class="menu-item"><a href="contact.php">Контакты</a></li>
												<li><a href="#" id="shift_style">Печать</a></li> 
                    </ul>
                </nav>
            </div>
        </header>


				<main class="main-content">
					<section class="about-section">
							<div class="container">
									<div class="titlepage">
											<h2>О нашей музыкальной продукции</h2>
									</div>
									<div class="about-content">
										<div class="about-description">
											<p><strong>Эксклюзивные треки:</strong> В нашем магазине вы найдете эксклюзивные треки, которые не доступны в обычных музыкальных магазинах. Эти треки могут включать редкие записи, ремиксы и живые выступления, которые позволят вам насладиться музыкой ваших любимых артистов в новом свете.</p>
											<p><strong>Треки с автографами:</strong> Мы предлагаем уникальную возможность приобрести треки с автографами популярных артистов. Эти эксклюзивные записи станут настоящим сокровищем для любого поклонника музыки и добавят особую ценность вашей коллекции.</p>
											<p><strong>Лимитированные издания:</strong> В нашем ассортименте также есть лимитированные издания альбомов и треков, которые выпускаются в ограниченном количестве. Эти издания часто включают дополнительные материалы, такие как постеры, буклеты и фотографии с автографами артистов.</p>
									</div>
									
											<div class="about-image">
													<img src="images/albom.png" alt="Music Merchandise" />
											</div>
									</div>
							</div>
					</section>
			
					<section class="testimonial-section">
							<div class="container">
									<div class="titlepage">
											<h2>Что говорят наши клиенты</h2>
									</div>
									<div class="testimonial">
											<div class="testimonial-item">
													<img src="images/people1.png" alt="Customer Image">
													<h4>Иван Смирнов</h4>
													<p>"Приобрел трек с автографом моего любимого артиста, и это стало настоящим сокровищем в моей коллекции. Автограф добавляет особую ценность, и я чувствую себя ближе к музыке, которую люблю. Рекомендую всем поклонникам музыки!"</p>
											</div>
											<div class="testimonial-item">
													<img src="images/people2.png" alt="Customer Image">
													<h4>Мария Иванова</h4>
													<p>"Лимитированные издания альбомов и треков в этом магазине просто невероятны! Я получила альбом с постером и буклетом с автографами артистов. Это действительно уникальный опыт, и я очень довольна своей покупкой. Спасибо за такие эксклюзивные предложения!"</p>
											</div>
											<div class="testimonial-item">
													<img src="images/peple3.png" alt="Customer Image">
													<h4>Екатерина Соколова</h4>
													<p>"Треки с автографами – это просто невероятно! Я приобрела несколько таких треков, и они стали настоящим украшением моей коллекции. Автографы добавляют особую ценность, и я чувствую себя ближе к музыке, которую люблю. Спасибо за такие уникальные предложения!"</p>
											</div>
									</div>
							</div>
					</section>
			</main>
			


        <footer class="site-footer">
            <div class="container">
                <img src="images/2.png" alt="Site Name">
                <address>
                    <p>Ленинградский просп., 80, корп. 17, Москва<br><a href="tel:8(950)-697-87-55">8(950)-697-87-55</a> <br> <a href="morozov95080@gmail.com">morozov95080@gmail.com</a></p> 
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
</body>
</html>
