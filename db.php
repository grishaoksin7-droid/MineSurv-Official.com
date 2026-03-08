<?php
$host = 'sql107.infinityfree.com';
$dbname = 'if0_41332396_minesurv';
$username = 'if0_41332396';
$password = 'frebijjjj';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>