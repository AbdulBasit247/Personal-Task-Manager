<?php
require 'db.php';
require 'helpers.php';
session_start();
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$msg = null;
if ($token) {
    $stmt = $pdo->prepare('SELECT pr.*, u.email FROM password_resets pr JOIN users u ON u.id=pr.user_id WHERE pr.token = ? AND pr.expires_at > NOW()');
    $stmt->execute([$token]);
    $row = $stmt->fetch();
    if (!$row) $msg = 'Invalid or expired token.';
    else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pw = $_POST['password'] ?? '';
            if ($pw) {
                $h = password_hash($pw, PASSWORD_DEFAULT);
                $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$h, $row['user_id']]);
                $pdo->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$row['user_id']]);
                set_flash('success','Password updated. You can log in now.');
                header('Location: login.php'); exit;
            } else $msg = 'Password required.';
        }
    }
} else $msg = 'No token provided.';
?>
<!doctype html><html><head><meta charset="utf-8"><title>Reset password</title><link rel="stylesheet" href="assets/css/style.css"></head><body class="centered">
<div class="card">
  <h3>Set a new password</h3>
  <?php if ($msg): ?><div class="error"><?=htmlspecialchars($msg)?></div><?php endif; ?>
  <?php if ($row): ?>
    <form method="post">
      <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
      <input name="password" placeholder="New password" type="password" required>
      <button type="submit">Save</button>
    </form>
  <?php endif; ?>
</div></body></html>
