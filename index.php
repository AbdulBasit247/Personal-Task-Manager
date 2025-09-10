<?php
require 'db.php';
require 'helpers.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}
$user_id = $_SESSION['user_id'];
// fetch user
$stmt = $pdo->prepare('SELECT id,username,email FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tasks - Task Manager</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div class="brand">TaskManager</div>
  <div class="right">
    <span><?=htmlspecialchars($user['username'])?></span>
    <a href="logout.php" class="btn">Logout</a>
  </div>
</header>
<main class="container">
  <section class="panel">
    <h2>Your Tasks</h2>
    <div class="controls">
      <input id="newTask" placeholder="Add a task and press Enter">
      <select id="priority">
        <option value="medium">Medium</option>
        <option value="low">Low</option>
        <option value="high">High</option>
      </select>
      <input id="due_date" type="date">
      <button id="addBtn">Add</button>
    </div>

    <div class="filters">
      <select id="filterStatus"><option value="all">All</option><option value="pending">Pending</option><option value="completed">Completed</option></select>
      <select id="sortBy"><option value="created_at">Created</option><option value="priority">Priority</option><option value="due_date">Due date</option></select>
    </div>

    <ul id="taskList" class="task-list"></ul>
  </section>
</main>

<script>
const API = 'api.php';
const uid = <?=json_encode($user['id'])?>;
</script>
<script src="assets/js/app.js"></script>
</body>
</html>
