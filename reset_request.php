<?php
require 'db.php';
require 'helpers.php';
$config = require 'config.php';
session_start();
$info = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expires = date('Y-m-d H:i:s', time() + 3600);
            $stmt = $pdo->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?,?,?)');
            $stmt->execute([$user['id'], $token, $expires]);
            $link = $config['SITE_URL'] . '/reset_password.php?token=' . $token;
            if (!empty($config['MAIL_FROM'])) {
                $to = $email;
                $subject = 'Password reset';
                $message = "Click to reset your password: $link";
                $headers = 'From: ' . $config['MAIL_FROM'];
                mail($to, $subject, $message, $headers);
                $info = 'Reset link sent to your email.';
            } else {
                $info = 'No mail configured. Your reset link: ' . $link;
            }
        } else {
            $info = 'If that email exists, a reset link was created.';
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Reset</title><link rel="stylesheet" href="assets/css/style.css"></head><body class="centered">
<div class="card">
  <h3>Password reset</h3>
  <?php if ($info): ?><div class="info"><?=htmlspecialchars($info)?></div><?php endif; ?>
  <form method="post">
    <input name="email" placeholder="Your email" required>
    <button type="submit">Request reset</button>
  </form>
  <p><a href="login.php">Back to login</a></p>
</div>
</body></html>
