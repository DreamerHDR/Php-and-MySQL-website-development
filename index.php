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
    <link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Music Site</title>
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
        <li class="menu-item current-menu-item"><a href="index.php">Главная</a></li>
        <li class="menu-item"><a href="about.php">О компании</a></li>
        <li class="menu-item"><a href="gallery.php">Каталог</a></li>
        <li class="menu-item"><a href="contact.php">Контакты</a></li>

        <li class="menu-item profile-menu">
            <!-- Проверка авторизации -->
						<?php if ($authorized): ?>
								<!-- Если авторизован, показываем ссылку на профиль -->
								<a href="php/profile.php">Профиль &bigtriangledown;</a>

								<!-- Показываем ссылку на таблицу треков только для администратора -->
								<?php if ($isAdmin): ?>
										<a href="php/table_track.php">Таблица треков &bigtriangledown;</a>
										<a href = "php/table_visist.php">Таблица пользователей &bigtriangledown;</a>
								<?php endif; ?>

								<ul class="submenu">
										<li><a href="php/logout.php">Выход</a></li>
								</ul>
						<?php else: ?>
                <!-- Если не авторизован, показываем кнопку входа -->
                <a href="#">Войти &bigtriangledown;</a>
                <ul class="submenu">
                    <li class="login-menu">
                        <a href="#">Войти</a>
                        <div class="dropdown-menu">
                            <form action="php/login.php" method="POST">
                                <label for="login-username">Логин</label>
                                <input type="text" id="login-username" name="login" required>
                                <label for="login-password">Пароль</label>
                                <input type="password" id="login-password" name="pass" required>
                                <button type="submit" name="submit">Войти</button>
                            </form>
														<div class="forgot-password">
                                <a href="php/forgot_password.php">Забыли пароль?</a>
                            </div>
                        </div>
                    </li>
                    <li class="register-menu">
                        <a href="#">Зарегистрироваться</a>
                        <div class="dropdown-menu">
                            <form action="php/register.php" method="POST">
                                <label for="register-username">Логин</label>
                                <input type="text" id="register-username" name="login" required>
                                <label for="register-password">Пароль</label>
                                <input type="password" id="register-password" name="password" required>
                                <label for="confirm-password">Подтвердите пароль</label>
                                <input type="password" id="confirm-password" name="confirm-password" required>
                                <label for="email">Почта</label>
                                <input type="email" id="email" name="email" required>
                                <button type="submit" name="submit">Зарегистрироваться</button>
                            </form>
                        </div>
                    </li>
                </ul>
            <?php endif; ?>
        </li>
    </ul>
</nav>


					</div>
    </header>

        <div class="hero">
            <div class="slider">
                <input type="radio" name="slider" id="slide1" checked>
                <input type="radio" name="slider" id="slide2">
                <input type="radio" name="slider" id="slide3">
                <div class="slides">
                    <div class="slide" id="s1">
                        <div class="text-container">
                            <div class="container1">
                                <h2 class="slide-title">Погружение в мир эмо-рэпа</h2>
                                <h3 class="slide-subtitle"></h3>
                                <p class="slide-desc">Узнайте больше о жизни и творчестве Lil Peep, одного из самых влиятельных артистов своего времени.<br>Его музыка и стиль оставили неизгладимый след в сердцах миллионов поклонников.</p>
                            </div>
                        </div>
                        <div class="image-container"></div>
                    </div>
                    <div class="slide" id="s2">
                        <div class="text-container">
                            <div class="container1">
                                <h2 class="slide-title">Восходящая звезда российского хип-хопа</h2>
                                <h3 class="slide-subtitle"></h3>
                                <p class="slide-desc">Откройте для себя Big Baby Tape, молодого и талантливого рэпера, который завоевал популярность благодаря своим уникальным битам и харизматичному исполнению.</p>
                            </div>
                        </div>
                        <div class="image-container"></div>
                    </div>
                    <div class="slide" id="s3">
                        <div class="text-container">
                            <div class="container1">
                                <h2 class="slide-title">Скандальный и яркий </h2>
                                <h3 class="slide-subtitle"></h3>
                                <p class="slide-desc">Познакомьтесь с 6ix9ine, артистом, который привлекает внимание не только своей музыкой, но и ярким внешним видом и скандальными выходками. <br>Узнайте больше о его пути к славе и трудностях, с которыми он столкнулся.</p>
                            </div>
                        </div>
                        <div class="image-container"></div>
                    </div>
                </div>

                <div class="controls">
                    <label for="slide1" class="control"></label>
                    <label for="slide2" class="control"></label>
                    <label for="slide3" class="control"></label>
                </div>
            </div>
        </div>

				<main class="main-content">
					<div class="fullwidth-block why-chooseus-section">
						<div class="container">
							<h2 class="section-title">Who would you choose?</h2>
							<div class="row">
								<div class="col-md-4">
									<div class="feature">
										<figure class="cut-corner">
											<img src="images/peep.jpg" alt="">
										</figure>
										<h3 class="feature-title">Lil Peep</h3>
										<p>Lil Peep был одним из пионеров жанра эмо-рэп, сочетая элементы хип-хопа и панк-рока. Он начал свою карьеру, выкладывая музыку на SoundCloud, и быстро завоевал популярность благодаря своим микстейпам и альбомам. Его дебютный альбом “Come Over When You’re Sober, Pt. 1” был выпущен в 2017 году.</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="feature">
										<figure class="cut-corner">
											<img src="images/tape.jpg" alt="">
										</figure>
										<h3 class="feature-title">Big Baby Tape</h3>
										<p>Big Baby Tape начал свою карьеру как битмейкер под псевдонимом DJ Tape. В 2017 году он выпустил свой первый мини-альбом “Cookin’ Anthems”, а в 2018 году - дебютный студийный альбом “Dragonborn”.</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="feature">
										<figure class="cut-corner">
											<img src="images/69.jpg" alt="">
										</figure>
										<h3 class="feature-title">6ix9ine</h3>
										<p>6ix9ine стал известен благодаря своему агрессивному стилю рэпа и яркому внешнему виду с радужными волосами и множеством татуировок. Его дебютный сингл “Gummo” стал хитом в 2017 году. В 2018 году он выпустил свой первый альбом “Dummy Boy”, который занял второе место в чарте Billboard 200.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</main>

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
		<script src="js/script.js"></script>
</body>
</html>