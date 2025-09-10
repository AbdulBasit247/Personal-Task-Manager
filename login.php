<?php
require 'db.php';
require 'helpers.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $e = trim($_POST['email'] ?? '');
    $p = $_POST['password'] ?? '';
    if (!$e || !$p) $errors[] = 'All fields required.';
    else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$e,$e]);
        $user = $stmt->fetch();
        if ($user && password_verify($p, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid credentials.';
        }
    }
}
$flash = flash();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login - Task Manager</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="centered">
<div class="card">
  <h2>Welcome back</h2>
  <?php if ($flash): ?><div class="<?=$flash['type']?>"><?=$flash['msg']?></div><?php endif; ?>
  <?php if ($errors): ?><div class="error"><?=htmlspecialchars(implode('<br>',$errors))?></div><?php endif; ?>
  <form method="post">
    <input name="email" placeholder="Email or username" required>
    <input name="password" placeholder="Password" type="password" required>
    <button type="submit">Login</button>
  </form>
  <p><a href="reset_request.php">Forgot password?</a></p>
  <p>No account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
