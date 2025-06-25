<?php
// Helper function to sanitize input
define('FIELDS', [
    'first_name', 'middle_name', 'last_name', 'username', 'password', 'confirm_password', 'birthday', 'email', 'contact_number'
]);

function get_post($key) {
    return isset($_POST[$key]) ? htmlspecialchars(trim($_POST[$key])) : '';
}

$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$data = [];
$error = '';
if ($submitted) {
    foreach (FIELDS as $field) {
        $data[$field] = get_post($field);
    }
    if ($data['password'] !== $data['confirm_password']) {
        $error = 'Password and confirm password are not the same.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <img src="https://upload.wikimedia.org/wikipedia/en/thumb/7/72/FEU_Tamaraws_official_logo.svg/1200px-FEU_Tamaraws_official_logo.svg.png" alt="FEU Logo" class="feu-logo">
    <h2>User Registration</h2>
    <form method="POST" autocomplete="off">
        <div>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required value="<?=get_post('first_name')?>">
        </div>
        <div>
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" value="<?=get_post('middle_name')?>">
        </div>
        <div>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required value="<?=get_post('last_name')?>">
        </div>
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required value="<?=get_post('username')?>">
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
            <input type="date" id="birthday" name="birthday" value="<?=get_post('birthday')?>">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?=get_post('email')?>">
        </div>
        <div>
            <label for="contact_number">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" required value="<?=get_post('contact_number')?>">
        </div>
        <button type="submit">Register</button>
    </form>

    <?php if ($submitted): ?>
        <?php if ($error): ?>
            <div class="error-message"> <?= $error ?> </div>
        <?php else: ?>
            <section class="profile-summary">
                <h3>Profile Summary</h3>
                <ul class="profile-list">
                    <li><span>First Name:</span> <span><?= $data['first_name'] ?></span></li>
                    <li><span>Middle Name:</span> <span><?= $data['middle_name'] ?></span></li>
                    <li><span>Last Name:</span> <span><?= $data['last_name'] ?></span></li>
                    <li><span>Username:</span> <span><?= $data['username'] ?></span></li>
                    <li><span>Birthday:</span> <span><?= $data['birthday'] ?></span></li>
                    <li><span>Email:</span> <span><?= $data['email'] ?></span></li>
                    <li><span>Contact Number:</span> <span><?= $data['contact_number'] ?></span></li>
                </ul>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
