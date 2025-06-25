<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: regismodules_login.php');
    exit();
}

require_once 'db.php';

// Fake dashboard items
$items = [
    ['name' => 'Item 1', 'desc' => 'This is a fake item.'],
    ['name' => 'Item 2', 'desc' => 'Another fake item.'],
    ['name' => 'Item 3', 'desc' => 'Sample dashboard item.'],
    ['name' => 'Item 4', 'desc' => 'Demo product.'],
];

// Retrieve user info from DB
$username = $_SESSION['user'];
$userinfo = [
    'fullname' => '',
    'birthday' => '',
    'email' => '',
    'contact' => ''
];
$stmt = $conn->prepare("SELECT first_name, middle_name, last_name, birthday, email, contact_number FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($fn, $mn, $ln, $bday, $email, $contact);
if ($stmt->fetch()) {
    $userinfo['fullname'] = trim("$fn $mn $ln");
    $userinfo['birthday'] = $bday ? date('F d, Y', strtotime($bday)) : '';
    $userinfo['email'] = $email;
    $userinfo['contact'] = $contact;
}
$stmt->close();

// Password change logic
$pw_error = '';
$pw_success = '';
if (isset($_POST['change_password'])) {
    $current = trim($_POST['current_password'] ?? '');
    $new = trim($_POST['new_password'] ?? '');
    $renew = trim($_POST['renew_password'] ?? '');
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed);
        $stmt->fetch();
        if (!password_verify($current, $hashed)) {
            $pw_error = 'Current password is not the same with the old password';
        } elseif ($new !== $renew) {
            $pw_error = 'New password and Re-Enter new password should be the same.';
        } else {
            $new_hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update->bind_param('ss', $new_hashed, $username);
            $update->execute();
            $update->close();
            $pw_success = 'Password changed successfully!';
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    body { background: #f6f8fa; }
    .dashboard-grid { display: flex; gap: 32px; justify-content: center; align-items: flex-start; margin: 40px 0; }
    .dashboard-items { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .item-card { background: #f9fafb; border-radius: 12px; box-shadow: 0 2px 8px rgba(60,72,100,0.07); padding: 18px 22px; min-width: 160px; max-width: 200px; text-align: center; }
    .item-card h4 { margin: 0 0 8px 0; color: #2563eb; }
    .side-panel { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(60,72,100,0.06); padding: 28px 22px; min-width: 270px; max-width: 320px; }
    .side-panel h3 { color: #2563eb; margin-top: 0; margin-bottom: 18px; }
    .user-info { text-align: left; margin-bottom: 18px; }
    .user-info strong { color: #374151; }
    .pw-form label { font-weight: 600; color: #3b4252; margin-bottom: 6px; display: block; }
    .pw-form input[type="password"] { width: 100%; padding: 10px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: #f9fafb; margin-bottom: 12px; }
    .pw-form button { background: linear-gradient(90deg, #6366f1 0%, #2563eb 100%); color: #fff; border: none; border-radius: 8px; padding: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .pw-form button:hover { background: linear-gradient(90deg, #2563eb 0%, #6366f1 100%); }
    .pw-success { background: #d1fae5; color: #047857; border: 1px solid #a7f3d0; border-radius: 10px; padding: 10px 14px; margin-bottom: 10px; text-align: center; }
    .pw-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 10px; padding: 10px 14px; margin-bottom: 10px; text-align: center; }
    .logout-link { display:inline-block;margin-top:24px;padding:10px 24px;background:#6366f1;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;transition:background 0.2s; }
    .logout-link:hover { background: #2563eb; }
    .footer { margin-top: 40px; text-align: center; color: #6b7280; font-size: 1rem; letter-spacing: 0.2px; }
    @media (max-width: 900px) {
      .dashboard-grid { flex-direction: column; align-items: center; }
      .side-panel { margin-bottom: 32px; }
    }
  </style>
</head>
<body>
<div class="dashboard-grid">
  <div class="dashboard-items">
    <?php foreach ($items as $item): ?>
      <div class="item-card">
        <h4><?= htmlspecialchars($item['name']) ?></h4>
        <p><?= htmlspecialchars($item['desc']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="side-panel">
    <h3>User Information Form</h3>
    <div class="user-info">
      <strong>Welcome <?= htmlspecialchars($userinfo['fullname']) ?></strong><br>
      Birthday: <?= htmlspecialchars($userinfo['birthday']) ?><br>
      <strong>Contact Details</strong><br>
      Email: <?= htmlspecialchars($userinfo['email']) ?><br>
      Contact: <?= htmlspecialchars($userinfo['contact']) ?>
    </div>
    <form class="pw-form" method="POST" autocomplete="off">
      <h4>RESET PASSWORD</h4>
      <?php if ($pw_error): ?><div class="pw-error"><?= htmlspecialchars($pw_error) ?></div><?php endif; ?>
      <?php if ($pw_success): ?><div class="pw-success"><?= htmlspecialchars($pw_success) ?></div><?php endif; ?>
      <label for="current_password">Enter Current Password:</label>
      <input type="password" id="current_password" name="current_password" required>
      <label for="new_password">Enter New Password:</label>
      <input type="password" id="new_password" name="new_password" required>
      <label for="renew_password">Re-Enter New Password:</label>
      <input type="password" id="renew_password" name="renew_password" required>
      <button type="submit" name="change_password">Reset Password</button>
    </form>
    <a href="logout.php" class="logout-link">Log-out</a>
  </div>
</div>
<div class="footer">© Crix Brix</div>
</body>
</html>
