<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="../style_login.css">
    <style>
        .info-box {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(60, 72, 100, 0.13);
            padding: 38px 28px 24px 28px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="info-box">
    <img src="https://upload.wikimedia.org/wikipedia/en/7/7c/Official_Far_Eastern_University_Logo.png" class="login-logo" alt="FEU Logo">
    <h2>Welcome to the Index Page</h2>
    <p>Please <a href="loginmodule.php">login</a> to continue.</p>
</div>
</body>
</html>
