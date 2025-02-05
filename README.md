# Php-and-MySQL-website-development
📌 Git README / Описание проекта

Проект: Веб-приложение с аутентификацией, каталогом треков и галереей изображений
🚀 Используемые технологии:

    Backend: PHP (сессии, работа с БД, авторизация)
    Frontend: HTML, CSS, JavaScript (jQuery)
    База данных: MySQL (пользователи, треки, посещения)
    Безопасность: Prepared Statements (mysqli_prepare), password_hash(), password_verify(), HttpOnly/Secure cookies

📂 Структура проекта:

    Основные страницы:
        /index.php – Главная
        /about.php – О проекте
        /contact.php – Контакты
        /gallery.php – Галерея
    Аутентификация:
        /login.php – Вход
        /register.php – Регистрация
        /profile.php – Профиль
        /forgot_password.php – Восстановление пароля
        /reset_password.php – Сброс пароля
        /logout.php – Выход
    Работа с БД:
        /db.php – Подключение к базе данных
        /get_tracks.php – Получение списка треков
        /table_track.php – Таблица треков
        /table_visist.php – Таблица посещений
        /track_visit.php – Логирование посещений
    Фронтенд:
        /css/ – Стили (style.css, style_about.css и др.)
        /js/ – Скрипты (jQuery, combo_list.js, script_gallery.js)
    Изображения:
        /images/ – Логотипы, обложки, галерея
        /uploads/ – Загруженные аватары и изображения

🛢 Структура базы данных:

    users (ID, имя, email, пароль, аватар)
    tracks (ID, название, исполнитель, альбом, год, жанр, обложка)
    visits (ID, пользователь, посещённые страницы, время)

🔒 Реализовано:

    Регистрация/вход с хешированными паролями
    Восстановление пароля через email
    Каталог треков
    Логирование посещений
    Загрузка аватаров и изображений
