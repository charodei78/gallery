<?php
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$username = 'root';
$password = 'root';
$host = 'localhost';
$port = '3306';
$db_name = 'gallery';
$connection = new PDO("mysql:host=$host;port=$port;dbname=$db_name",
    $username, $password, $options);
