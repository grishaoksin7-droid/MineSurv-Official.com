<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: main.php');
    exit;
}

$error = '';

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
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MineSurv - Вход</title>
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
        
        .login-btn {
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
        
        .switch a {
            color: #ffff55;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        <h2>Войдите</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="input-group">
                <label>Ваш ник для аккаунта</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="input-group">
                <label>Ваш пароль</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" name="login" class="login-btn">Войти</button>
        </form>
        
        <div class="switch">
            <a href="index.php">Нет аккаунта? Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>