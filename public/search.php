<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_login();

$q = $_GET['q'] ?? '';
$status = $_GET['status'] ?? 'all';
$today = date('Y-m-d');

$sql = "SELECT * FROM members WHERE (full_name LIKE ? OR phone LIKE ?)";
$params = ["%$q%", "%$q%"];

if ($status === 'active') {
    $sql .= " AND end_date >= ?";
    $params[] = $today;
} elseif ($status === 'expired') {
    $sql .= " AND end_date < ?";
    $params[] = $today;
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$res = $stmt->fetchAll();

foreach($res as &$r) {
    $r['status'] = ($today <= $r['end_date']) ? 'Active' : 'Expired';
    $r['start_display'] = d($r['start_date']);
    $r['end_display'] = d($r['end_date']);
    $r['full_name'] = h($r['full_name']);
    $r['photo'] = empty($r['photo']) ? 'default.png' : $r['photo'];
}

echo json_encode($res);