<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("UPDATE users SET is_admin = 1 WHERE id = ? AND username != 'MysteryMan993'");
$stmt->execute([$id]);

header('Location: admin.php');
exit;
?>