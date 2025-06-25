<?php
// Database config (edit as needed)
$host = 'localhost';
$db = 'registration_db';
$user = 'root';
$pass = '';

// Connect to MySQL
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
// Create DB if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);
// Create users table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    birthday DATE,
    email VARCHAR(100) NOT NULL,
    contact_number VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$fields = ['first_name','middle_name','last_name','username','password','confirm_password','birthday','email','contact_number'];
$data = [];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $f) {
        $data[$f] = isset($_POST[$f]) ? trim($_POST[$f]) : '';
    }
    // Validation
    foreach (['first_name','last_name','username','password','confirm_password','birthday','email','contact_number'] as $f) {
        if (empty($data[$f])) {
            $error = 'All fields are required.';
            break;
        }
    }
    if (!$error && $data['password'] !== $data['confirm_password']) {
        $error = 'password and confirm password are not the same';
    }
    // Insert if valid
    if (!$error) {
        // Check if username already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $check->bind_param('s', $data['username']);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = 'Username already exists. Please choose another.';
        } else {
            $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, username, password, birthday, email, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('ssssssss', $data['first_name'], $data['middle_name'], $data['last_name'], $data['username'], $hashed, $data['birthday'], $data['email'], $data['contact_number']);
            if ($stmt->execute()) {
                $success = 'Registration successful!';
                $data = [];
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
  <h2>User Registration</h2>
  <?php if ($error): ?>
    <div class="error-message"> <?= htmlspecialchars($error) ?> </div>
  <?php elseif ($success): ?>
    <div class="success-message"> <?= htmlspecialchars($success) ?> </div>
  <?php endif; ?>
  <form method="POST" autocomplete="off">
    <div>
      <label for="first_name">First Name</label>
      <input type="text" id="first_name" name="first_name" required value="<?= isset($data['first_name']) ? htmlspecialchars($data['first_name']) : '' ?>">
    </div>
    <div>
      <label for="middle_name">Middle Name</label>
      <input type="text" id="middle_name" name="middle_name" value="<?= isset($data['middle_name']) ? htmlspecialchars($data['middle_name']) : '' ?>">
    </div>
    <div>
      <label for="last_name">Last Name</label>
      <input type="text" id="last_name" name="last_name" required value="<?= isset($data['last_name']) ? htmlspecialchars($data['last_name']) : '' ?>">
    </div>
    <div>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required value="<?= isset($data['username']) ? htmlspecialchars($data['username']) : '' ?>">
    </div>
    <div>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>
    <div>
      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
      <label for="birthday">Birthday</label>
      <input type="date" id="birthday" name="birthday" required value="<?= isset($data['birthday']) ? htmlspecialchars($data['birthday']) : '' ?>">
    </div>
    <div>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?= isset($data['email']) ? htmlspecialchars($data['email']) : '' ?>">
    </div>
    <div>
      <label for="contact_number">Contact Number</label>
      <input type="text" id="contact_number" name="contact_number" required value="<?= isset($data['contact_number']) ? htmlspecialchars($data['contact_number']) : '' ?>">
    </div>
    <button type="submit">Register</button>
  </form>
  <div class="login-footer" style="margin-top:32px;">© Crix Brix</div>
</div>
</body>
</html>
