<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$message = trim($_POST['message'] ?? '');

if ($message) {
    $stmt = $pdo->prepare("INSERT INTO admin_chat (user_id, username, message) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['username'], $message]);
}

header('Location: admin.php');
exit;
?>