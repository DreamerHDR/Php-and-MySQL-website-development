<?php
// Удаляем все сессионные данные
session_start();

// Удаляем куки, если они есть
setcookie('user_id', '', time() - 3600, '/');
setcookie('user_hash', '', time() - 3600, '/');

session_destroy();

// Перенаправляем на главную страницу
header("Location: ../index.php");
exit;
?>
