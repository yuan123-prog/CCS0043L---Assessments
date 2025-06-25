<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: loginmodule.php');
    exit();
}
$username = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../style_login.css">
    <style>
        .home-container {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(60, 72, 100, 0.13);
            padding: 38px 28px 24px 28px;
            text-align: center;
        }
        .home-username {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 18px;
        }
        .logout-link {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 22px;
            background: linear-gradient(90deg, #6366f1 0%, #2563eb 100%);
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }
        .logout-link:hover {
            background: linear-gradient(90deg, #2563eb 0%, #6366f1 100%);
        }
    </style>
</head>
<body>
<div class="home-container">
    <img src="https://upload.wikimedia.org/wikipedia/en/7/7c/Official_Far_Eastern_University_Logo.png" class="login-logo" alt="FEU Logo">
    <div class="home-username">Welcome, <?= htmlspecialchars($username) ?>!</div>
    <a href="logout.php" class="logout-link">Logout</a>
</div>
</body>
</html>
