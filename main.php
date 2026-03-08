<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$is_admin = $_SESSION['is_admin'];

// Получаем уведомления
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MineSurv - Главная</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(180deg, #87CEEB 0%, #87CEEB 60%, #5a8c5a 60%);
            min-height: 100vh;
        }
        
        .navbar {
            background: #333;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #4a4a4a;
        }
        
        .logo {
            color: #ffff55;
            font-size: 20px;
            font-weight: bold;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }
        
        #username {
            color: #fff;
            cursor: pointer;
            padding: 5px 10px;
            border: 2px solid transparent;
        }
        
        #username:hover {
            border: 2px solid #ffff55;
        }
        
        .logout-btn {
            background: #8b8b8b;
            border: 2px solid #f0f0f0;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
            padding: 5px 15px;
            cursor: pointer;
            color: #fff;
            font-family: 'Courier New', monospace;
            text-decoration: none;
        }
        
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
        }
        
        h1 {
            font-size: 72px;
            color: #ffff55;
            text-shadow: 4px 4px 0 #3f3f00;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #fff;
            font-size: 18px;
            margin-bottom: 60px;
            text-shadow: 2px 2px 0 #2d2d2d;
        }
        
        .buttons {
            display: flex;
            gap: 50px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 40px;
        }
        
        .button-link {
            text-decoration: none;
        }
        
        .minecraft-button {
            display: inline-block;
            font-family: 'Courier New', monospace;
        }
        
        .button-top {
            color: #aaa;
            font-size: 16px;
            line-height: 1.5;
        }
        
        .button-middle {
            color: #fff;
            background: #8b8b8b;
            padding: 10px 20px;
            border-left: 4px solid #f0f0f0;
            border-right: 4px solid #4a4a4a;
            font-size: 16px;
            font-weight: bold;
            text-shadow: 2px 2px 0 #4a4a4a;
        }
        
        .button-bottom {
            color: #aaa;
            font-size: 16px;
            line-height: 1.5;
        }
        
        .button-link:hover .button-middle {
            background: #9b9b9b;
        }
        
        .admin-button .button-middle {
            background: #aa5500;
        }
        
        .notification-badge {
            background: #ff5555;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            position: absolute;
            top: -5px;
            right: -5px;
        }
        
        @media (max-width: 600px) {
            h1 { font-size: 36px; }
            .buttons { gap: 30px; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">⛏️ MineSurv</div>
        <div class="user-info">
            <span id="username"><?= htmlspecialchars($username) ?></span>
            <?php if (count($notifications) > 0): ?>
                <span class="notification-badge"><?= count($notifications) ?></span>
            <?php endif; ?>
            <a href="logout.php" class="logout-btn">Выйти</a>
        </div>
    </div>

    <div class="main-content">
        <h1>MineSurv</h1>
        <div class="subtitle">⬇️ Подайте заявку ⬇️</div>
        
        <div class="buttons">
            <a href="request.php" class="button-link">
                <div class="minecraft-button">
                    <div class="button-top">⌈⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⌉</div>
                    <div class="button-middle">│  Заявка   │</div>
                    <div class="button-bottom">⌊ˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍ⌋</div>
                </div>
            </a>
            
            <a href="msma.php" class="button-link">
                <div class="minecraft-button">
                    <div class="button-top">⌈⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⌉</div>
                    <div class="button-middle">│  Заявка на MSMAA  │</div>
                    <div class="button-bottom">⌊ˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍ⌋</div>
                </div>
            </a>
        </div>
        
        <?php if ($is_admin): ?>
            <div class="admin-panel-btn">
                <a href="admin.php" class="button-link admin-button">
                    <div class="minecraft-button">
                        <div class="button-top">⌈⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⎺⌉</div>
                        <div class="button-middle">│  Админская панель  │</div>
                        <div class="button-bottom">⌊ˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍˍ⌋</div>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>