<?php
$host = 'fdb1032.awardspace.net';
$dbname = '4741265_minesurvofficicalss';
$username = '4741265_minesurvofficicalss';
$password = 'Z6v-mVQ-HWe-gmj';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
