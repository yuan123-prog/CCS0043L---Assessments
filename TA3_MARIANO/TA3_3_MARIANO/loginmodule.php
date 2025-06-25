<?php
session_start();

class Auth {
    public static $USERNAME = 'yuanmariano'; // Change to your username
    public static $PASSWORD = '123'; // Change to your password
}

$error = '';

if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    if ($username === Auth::$USERNAME && $password === Auth::$PASSWORD) {
        $_SESSION['user'] = $username;
        header('Location: home.php');
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Module</title>
    <link rel="stylesheet" href="../style_login.css">
</head>
<body>
<div class="login-container">
    <img src="https://upload.wikimedia.org/wikipedia/en/7/7c/Official_Far_Eastern_University_Logo.png" class="login-logo" alt="FEU Logo">
    <div class="login-title">Login to Your Account</div>
    <?php if ($error): ?>
        <div class="error-message" style="margin-bottom:12px;"> <?= htmlspecialchars($error) ?> </div>
    <?php endif; ?>
    <form method="POST" autocomplete="off">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required autofocus>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="login-footer">© GENE JUSTINE P. ROSALES</div>
</div>
</body>
</html>