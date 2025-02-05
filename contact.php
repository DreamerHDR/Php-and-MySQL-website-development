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
    <link rel="stylesheet" href="css/style_contact.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Contact</title>
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
                        <li class="menu-item"><a href="about.php">О компании</a></li>
                        <li class="menu-item"><a href="gallery.php">Каталог</a></li>
                        <li class="menu-item current-menu-item"><a href="contact.php">Контакты</a></li>
                    </ul>
                </nav>
            </div>
        </header>


        <div class="contact-container">
            <div class="contact-form">
                <h2>Contact Us</h2>
                <form action="#" method="post">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                    <button type="submit">Send Message</button>
                </form>
            </div>

            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2102.0095298021115!2d37.508237712276724!3d55.80810797299255!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b548351529d9a3%3A0x64ce87e9305fab1c!2sVK%20Stadium!5e1!3m2!1sru!2sru!4v1726070758429!5m2!1sru!2sru"
                    frameborder="0" allowfullscreen></iframe>
            </div>
        </div>


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
    <script src="js/script.js"></script>
</body>
</html>
