<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

// Получаем всех пользователей
$users = $pdo->query("SELECT id, username, is_admin, registered FROM users ORDER BY id")->fetchAll();

// Получаем заявки
$requests = $pdo->query("SELECT r.*, u.username as user_login FROM requests r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC")->fetchAll();

// Получаем заявки MSMAA
$msma_requests = $pdo->query("SELECT r.*, u.username as user_login FROM msma_requests r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC")->fetchAll();

// Получаем сообщения чата
$chat = $pdo->query("SELECT * FROM admin_chat ORDER BY created_at DESC LIMIT 50")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MineSurv - Админ панель</title>
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
            padding: 20px;
        }
        
        .navbar {
            background: #333;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #4a4a4a;
            margin-bottom: 30px;
        }
        
        .logo {
            color: #ffff55;
            font-size: 20px;
            font-weight: bold;
        }
        
        .back-btn {
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: #ffff55;
            text-shadow: 2px 2px 0 #3f3f00;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .section {
            background: #c6c6c6;
            padding: 20px;
            border: 4px solid #ffffff;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #ffff55;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: #8b8b8b;
        }
        
        th, td {
            padding: 10px;
            border: 2px solid #4a4a4a;
            color: #fff;
        }
        
        th {
            background: #4a4a4a;
            color: #ffff55;
        }
        
        .btn {
            padding: 5px 10px;
            background: #5a8c5a;
            border: 2px solid #f0f0f0;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        
        .chat-box {
            background: #2d2d2d;
            padding: 10px;
            height: 300px;
            overflow-y: auto;
            margin-bottom: 10px;
            border: 2px solid #4a4a4a;
        }
        
        .chat-message {
            margin-bottom: 10px;
            padding: 5px;
            border-bottom: 1px solid #4a4a4a;
            color: #fff;
        }
        
        .chat-input {
            display: flex;
            gap: 10px;
        }
        
        .chat-input input {
            flex: 1;
            padding: 10px;
            background: #2d2d2d;
            border: 2px solid #4a4a4a;
            color: #ffff55;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">⛏️ MineSurv - Админ панель</div>
        <a href="main.php" class="back-btn">← Назад</a>
    </div>

    <div class="container">
        <div class="section">
            <h2>👑 Пользователи</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Ник</th>
                    <th>Статус</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                </tr>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= $user['is_admin'] ? '👑 Админ' : '👤 Игрок' ?></td>
                    <td><?= $user['registered'] ?></td>
                    <td>
                        <?php if (!$user['is_admin'] && $user['username'] != 'MysteryMan993'): ?>
                        <a href="make_admin.php?id=<?= $user['id'] ?>" class="btn" onclick="return confirm('Выдать админку?')">Сделать админом</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="section">
            <h2>📋 Заявки</h2>
            <table>
                <tr>
                    <th>Пользователь</th>
                    <th>Ник в Minecraft</th>
                    <th>Время игры</th>
                    <th>Откуда узнал</th>
                    <th>Навыки</th>
                    <th>Дата</th>
                </tr>
                <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['user_login']) ?></td>
                    <td><?= htmlspecialchars($req['nickname']) ?></td>
                    <td><?= htmlspecialchars($req['play_time']) ?></td>
                    <td><?= htmlspecialchars($req['source']) ?></td>
                    <td><?= htmlspecialchars($req['skill']) ?></td>
                    <td><?= $req['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="section">
            <h2>⚔️ Заявки MSMAA</h2>
            <table>
                <tr>
                    <th>Пользователь</th>
                    <th>Ник в Minecraft</th>
                    <th>Время игры</th>
                    <th>Откуда узнал</th>
                    <th>Навыки</th>
                    <th>Дата</th>
                </tr>
                <?php foreach ($msma_requests as $req): ?>
                <tr>
                    <td><?= htmlspecialchars($req['user_login']) ?></td>
                    <td><?= htmlspecialchars($req['nickname']) ?></td>
                    <td><?= htmlspecialchars($req['play_time']) ?></td>
                    <td><?= htmlspecialchars($req['source']) ?></td>
                    <td><?= htmlspecialchars($req['skill']) ?></td>
                    <td><?= $req['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="section">
            <h2>💬 Чат администраторов</h2>
            <div class="chat-box" id="chatBox">
                <?php foreach (array_reverse($chat) as $msg): ?>
                <div class="chat-message">
                    <strong><?= htmlspecialchars($msg['username']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?>
                    <div style="color: #aaa; font-size: 10px;"><?= $msg['created_at'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <form class="chat-input" method="POST" action="send_message.php">
                <input type="text" name="message" placeholder="Введите сообщение..." required>
                <button type="submit" class="btn">Отправить</button>
            </form>
        </div>
    </div>
</body>
</html>