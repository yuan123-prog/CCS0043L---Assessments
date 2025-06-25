<?php
session_start();
require_once 'db.php';

$error = '';
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    if ($username && $password) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed);
            $stmt->fetch();
            if (password_verify($password, $hashed)) {
                $_SESSION['user'] = $username;
                header('Location: home.php');
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .login-box { max-width: 400px; margin: 60px auto; background: #fff; border-radius: 18px; box-shadow: 0 8px 32px rgba(60,72,100,0.13); padding: 38px 28px 24px 28px; text-align: center; }
    .login-box h2 { color: #2563eb; margin-bottom: 24px; }
    .login-box form { display: flex; flex-direction: column; gap: 18px; }
    .login-box label { font-weight: 600; color: #3b4252; margin-bottom: 6px; text-align: left; }
    .login-box input[type="text"], .login-box input[type="password"] { width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 1.05rem; background: #f9fafb; transition: border 0.2s, box-shadow 0.2s; box-shadow: 0 1px 2px rgba(60,72,100,0.03); }
    .login-box input:focus { border-color: #6366f1; outline: none; box-shadow: 0 0 0 2px #c7d2fe; }
    .login-box button[type="submit"] { background: linear-gradient(90deg, #6366f1 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; padding: 14px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: background 0.2s, box-shadow 0.2s; margin-top: 8px; box-shadow: 0 2px 8px rgba(99,102,241,0.08); }
    .login-box button[type="submit"]:hover { background: linear-gradient(90deg, #2563eb 0%, #6366f1 100%); box-shadow: 0 4px 16px rgba(99,102,241,0.13); }
    .login-footer { margin-top: 24px; text-align: center; font-size: 0.98rem; color: #6b7280; letter-spacing: 0.2px; }
    .error-message { margin-top: 18px; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 10px; padding: 14px 18px; font-size: 1.08rem; text-align: center; font-weight: 600; box-shadow: 0 2px 8px rgba(185,28,28,0.04); }
  </style>
</head>
<body>
<div class="login-box">
  <h2>Log-in-form</h2>
  <?php if ($error): ?>
    <div class="error-message"> <?= htmlspecialchars($error) ?> </div>
  <?php endif; ?>
  <form method="POST" autocomplete="off">
    <div>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required autofocus>
    </div>
    <div>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Login</button>
  </form>
  <div class="login-footer">@Crix Brix</div>
</div>
</body>
</html>
