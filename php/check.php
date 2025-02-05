<?
$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    // Исправленный запрос
    $query = mysqli_query($conn, "SELECT id, user_hash, INET_NTOA(user_ip) AS user_ip FROM Users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);

    if (($userdata['user_hash'] !== $_COOKIE['hash']) or 
        ($userdata['id'] !== $_COOKIE['id']) or 
        (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR']) and 
        ($userdata['user_ip'] !== "0")))
    {
        setcookie("id", "", time() - 3600, "/");
        setcookie("hash", "", time() - 3600, "/");
        print "что-то не получилось";
    }
    else
    {
				setcookie("user_id", $userdata['id'], time() + 3600, "/");  // Кука истечет через 1 час
        setcookie("user_hash", $userdata['user_hash'], time() + 3600, "/");
        //print "Привет, ".$userdata['login'].". Всё работает!";
        header("Location: ../index.php"); exit();
    }
}
else
{
    print "Включите куки";
}
?>