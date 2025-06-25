<?php
// Set cookie duration (7 days)
$cookie_duration = 60 * 60 * 24 * 7;

// Get cookie values if they exist
$cookie_username = isset($_COOKIE['login_username']) ? $_COOKIE['login_username'] : '';
$cookie_password = isset($_COOKIE['login_password']) ? $_COOKIE['login_password'] : '';

// Get POST values
$username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : $cookie_username;
$password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : $cookie_password;
$remember = isset($_POST['remember']);

// On submit, set cookies if Remember Me is checked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($remember) {
        setcookie('login_username', $username, time() + $cookie_duration, '/');
        setcookie('login_password', $password, time() + $cookie_duration, '/');
    } else {
        setcookie('login_username', '', time() - 3600, '/');
        setcookie('login_password', '', time() - 3600, '/');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../style_login.css">
</head>
<body>
<div class="login-container">
    <img src="https://upload.wikimedia.org/wikipedia/en/7/7c/Official_Far_Eastern_University_Logo.png">
    <div class="login-title">Login to Your Account</div>
    <form method="POST" autocomplete="off">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required value="<?= htmlspecialchars($username) ?>">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required value="<?= htmlspecialchars($password) ?>" autocomplete="current-password">
        </div>
        <div class="remember-row">
            <input type="checkbox" id="remember" name="remember" <?= $remember || $cookie_username ? 'checked' : '' ?>>
            <label for="remember" style="margin-bottom:0; font-weight:400;">Remember Me</label>
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="login-footer">© Nicanor Baptista Reyes Sr.</div>
</div>
</body>
</html>
