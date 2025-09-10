<?php
require 'db.php';
require 'helpers.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $e = trim($_POST['email'] ?? '');
    $p = $_POST['password'] ?? '';
    if (!$u || !$e || !$p) $errors[] = 'All fields are required.';
    if (!filter_var($e, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
    if (!$errors) {
        // check existing
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username=? OR email=?');
        $stmt->execute([$u,$e]);
        if ($stmt->fetch()) $errors[] = 'Username or email already exists.';
        else {
            $hash = password_hash($p, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username,email,password) VALUES (?,?,?)');
            $stmt->execute([$u,$e,$hash]);
            set_flash('success','Registration successful. You can log in now.');
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register - Task Manager</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="centered">
<div class="card">
  <h2>Create account</h2>
  <?php if ($errors): ?>
    <div class="error"><?=htmlspecialchars(implode('<br>',$errors))?></div>
  <?php endif; ?>
  <form method="post">
    <input name="username" placeholder="Username" required>
    <input name="email" placeholder="Email" type="email" required>
    <input name="password" placeholder="Password" type="password" required>
    <button type="submit">Register</button>
  </form>
  <p>Already have account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
