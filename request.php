<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = trim($_POST['nickname']);
    $playTime = trim($_POST['playTime']);
    $source = trim($_POST['source']);
    $skill = trim($_POST['skill']);
    
    $stmt = $pdo->prepare("INSERT INTO requests (user_id, nickname, play_time, source, skill) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $nickname, $playTime, $source, $skill]);
    
    header('Location: main.php?success=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка на MineSurv</title>
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
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #c6c6c6;
            padding: 30px;
            border: 4px solid #ffffff;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
        }
        
        h1 {
            color: #ffff55;
            text-shadow: 2px 2px 0 #3f3f00;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .question {
            margin-bottom: 25px;
            padding: 15px;
            background: #8b8b8b;
            border: 2px solid #f0f0f0;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
        }
        
        .number {
            color: #ffff55;
            font-size: 20px;
            margin-right: 10px;
            display: inline-block;
        }
        
        .label {
            color: #fff;
            text-shadow: 1px 1px 0 #000;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: #2d2d2d;
            border: 2px solid #4a4a4a;
            color: #ffff55;
            font-family: 'Courier New', monospace;
        }
        
        .buttons {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }
        
        .btn {
            flex: 1;
            padding: 15px;
            border: 4px solid #f0f0f0;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            color: #fff;
        }
        
        .btn-back {
            background: #8b8b8b;
        }
        
        .btn-submit {
            background: #5a8c5a;
        }
        
        .btn:hover {
            filter: brightness(1.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Вы выбрали: Заявка на MineSurv</h1>
        
        <form method="POST">
            <div class="question">
                <span class="number">1.</span>
                <span class="label">Ваш никнейм в Minecraft</span>
                <input type="text" name="nickname" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
            </div>
            
            <div class="question">
                <span class="number">2.</span>
                <span class="label">Сколько вы играете?</span>
                <input type="text" name="playTime" placeholder="Например: 2 года" required>
            </div>
            
            <div class="question">
                <span class="number">3.</span>
                <span class="label">Откуда вы узнали о сервере?</span>
                <input type="text" name="source" placeholder="Например: YouTube, друг" required>
            </div>
            
            <div class="question">
                <span class="number">4.</span>
                <span class="label">В чём вы больше всего лучше? (Например: Строительство, механизмы)</span>
                <textarea name="skill" rows="3" required></textarea>
            </div>
            
            <div class="buttons">
                <a href="main.php" class="btn btn-back">↩️ Назад</a>
                <button type="submit" class="btn btn-submit">✅ Отправить заявку</button>
            </div>
        </form>
    </div>
</body>
</html>