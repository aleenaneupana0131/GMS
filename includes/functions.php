<?php
session_start();
date_default_timezone_set('Asia/Kathmandu');


// XSS Protection
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Auth Check
function require_login() {
    if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
}

// CSRF Protection
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) die("CSRF Error");
}

// Date Formatter
function d($date) {
    return date('M j, Y', strtotime($date));
}
?>