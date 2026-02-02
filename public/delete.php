<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_login();
if (isset($_GET['id'])) {
    $pdo->prepare("DELETE FROM members WHERE id = ?")->execute([$_GET['id']]);
}
header("Location: dashboard.php");