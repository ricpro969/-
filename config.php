<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'concert_hall';

$connect = mysqli_connect($host, $user, $password, $dbname);

if (!$connect) {
    die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
}

mysqli_set_charset($connect, 'utf8mb4');
?>