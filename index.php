<?php
session_start();
require_once 'db.php';

// Если уже авторизован, отправляем на главную
if (isset($_SESSION['user_id'])) {
    header('Location: main.php');
    exit;
}

$error = '';

// Обработка регистрации
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
    
    // Простая капча (в сессии)
    if ($_SESSION['captcha'] != $captcha) {
        $error = 'Неверная капча';
    } else {
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $error = 'Пользователь уже существует';
        } else {
            // Хешируем пароль
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Добавляем пользователя
            $stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin, registered) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$username, $hash, 0]);
            
            $userId = $pdo->lastInsertId();
            
            // Автоматический вход
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = 0;
            
            header('Location: main.php');
            exit;
        }
    }
}

// Обработка входа
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header('Location: main.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}

// Генерация капчи
$num1 = rand(1, 9);
$num2 = rand(1, 9);
$_SESSION['captcha'] = $num1 + $num2;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MineSurv - Вход / Регистрация</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(180deg, #87CEEB 0%, #5a8c5a 70%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .auth-box {
            background: #c6c6c6;
            padding: 20px;
            border: 4px solid #ffffff;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
            max-width: 500px;
            width: 90%;
        }
        
        h2 {
            text-align: center;
            color: #ffff55;
            text-shadow: 2px 2px 0 #3f3f00;
            margin-bottom: 20px;
        }
        
        .input-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
            text-shadow: 1px 1px 0 #000;
        }
        
        input {
            width: 100%;
            padding: 10px;
            background: #2d2d2d;
            border: 2px solid #4a4a4a;
            color: #ffff55;
            font-family: 'Courier New', monospace;
        }
        
        .captcha-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .captcha-box {
            display: flex;
            gap: 5px;
            align-items: center;
            flex: 1;
        }
        
        .captcha-text {
            background: #2d2d2d;
            padding: 8px;
            border: 2px solid #4a4a4a;
            color: #ffff55;
            white-space: nowrap;
        }
        
        .captcha-input {
            flex: 1;
        }
        
        .refresh-btn {
            padding: 8px 12px;
            background: #8b8b8b;
            border: 2px solid #f0f0f0;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
            cursor: pointer;
        }
        
        .auth-link {
            color: #ffff55;
            text-decoration: none;
        }
        
        .register-btn {
            width: 100%;
            padding: 15px;
            background: #5a8c5a;
            border: 4px solid #f0f0f0;
            border-right-color: #4a4a4a;
            border-bottom-color: #4a4a4a;
            color: #fff;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            cursor: pointer;
        }
        
        .error {
            color: #ff5555;
            text-align: center;
            margin: 10px 0;
        }
        
        .switch {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        <h2>Зарегистрируйтесь</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="input-group">
                <label>Придумайте ник для аккаунта</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="input-group">
                <label>Придумайте пароль</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="captcha-row">
                <div class="captcha-box">
                    <span class="captcha-text"><?= $num1 ?> + <?= $num2 ?> = ?</span>
                    <input type="text" name="captcha" class="captcha-input" required>
                    <button type="button" class="refresh-btn" onclick="location.reload()">🔄</button>
                </div>
                <a href="login.php" class="auth-link">Уже имеете аккаунт? Войти</a>
            </div>
            
            <button type="submit" name="register" class="register-btn">Зарегистрироваться</button>
        </form>
    </div>
</body>
</html>