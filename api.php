<?php
// API endpoint for tasks (simple)
require 'db.php';
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); echo json_encode(['error'=>'unauth']); exit;
}
$uid = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
if ($method === 'GET' && $action === 'list') {
    $status = $_GET['status'] ?? 'all';
    $sort = $_GET['sort'] ?? 'created_at';
    $validSort = ['created_at','priority','due_date'];
    if (!in_array($sort,$validSort)) $sort = 'created_at';
    $sql = 'SELECT * FROM tasks WHERE user_id = ?';
    $params = [$uid];
    if ($status === 'pending') { $sql .= ' AND completed = 0'; }
    if ($status === 'completed') { $sql .= ' AND completed = 1'; }
    $sql .= ' ORDER BY ' . $sort . ' DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $res = $stmt->fetchAll();
    echo json_encode($res); exit;
}
if ($method === 'POST' && $action === 'create') {
    $data = json_decode(file_get_contents('php://input'), true);
    $task = $data['task'] ?? '';
    $priority = in_array($data['priority'] ?? 'medium', ['low','medium','high']) ? $data['priority'] : 'medium';
    $due = $data['due_date'] ?: null;
    $stmt = $pdo->prepare('INSERT INTO tasks (user_id,task,priority,due_date) VALUES (?,?,?,?)');
    $stmt->execute([$uid,$task,$priority,$due]);
    echo json_encode(['ok'=>1,'id'=>$pdo->lastInsertId()]); exit;
}
if ($method === 'POST' && $action === 'update') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    $field = $data['field'] ?? '';
    $allowed = ['task','completed','priority','due_date'];
    if (!in_array($field,$allowed)) { http_response_code(400); echo json_encode(['error'=>'badfield']); exit; }
    $value = $data['value'];
    // simple update
    $stmt = $pdo->prepare("UPDATE tasks SET $field = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$value,$id,$uid]);
    echo json_encode(['ok'=>1]); exit;
}
if ($method === 'POST' && $action === 'delete') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)($data['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
    $stmt->execute([$id,$uid]);
    echo json_encode(['ok'=>1]); exit;
}
http_response_code(400);
echo json_encode(['error'=>'unknown']);
