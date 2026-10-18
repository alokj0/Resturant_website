<?php
// Database settings (XAMPP defaults: user root, empty password)
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=restaurant_db;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $ex) {
    exit('Database connection failed. Start MySQL in XAMPP and import database.sql.');
}
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function csrf() { if (empty($_SESSION['t'])) $_SESSION['t'] = bin2hex(random_bytes(16)); return $_SESSION['t']; }
function csrf_field() { return '<input type="hidden" name="t" value="' . csrf() . '">'; }
function check_csrf() { if (!hash_equals($_SESSION['t'] ?? '', $_POST['t'] ?? '')) { http_response_code(400); exit('Invalid request'); } }
function require_admin() { if (empty($_SESSION['admin'])) { header('Location: login.php'); exit; } }
function current_user() {
    global $pdo; static $u = false;
    if ($u === false) {
        $u = null;
        if (!empty($_SESSION['user_id'])) {
            $s = $pdo->prepare('SELECT * FROM users WHERE id=?'); $s->execute([$_SESSION['user_id']]);
            $u = $s->fetch() ?: null;
        }
    }
    return $u;
}
