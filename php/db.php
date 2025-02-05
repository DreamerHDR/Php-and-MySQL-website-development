<?php

$servername = "127.127.126.50";
$username = "root";
$password = "";
$dbname = "MusicBase";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
	die("Connection Failed". mysqli_connect_error());
} else{
	echo "Успех";
}
?>