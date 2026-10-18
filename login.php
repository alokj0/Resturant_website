<?php
require '../config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $s = $pdo->prepare('SELECT * FROM admins WHERE username=?');
    $s->execute([trim($_POST['username'] ?? '')]);
    $a = $s->fetch();
    if ($a && password_verify($_POST['password'] ?? '', $a['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin'] = $a['username'];
        header('Location: index.php'); exit;
    }
    $err = 'Wrong username or password.';
}
?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin login</title><link rel="stylesheet" href="../assets/style.css"></head>
<body class="visit" style="min-height:100vh"><div class="wrap" style="padding-top:12vh">
<h2>Admin login</h2>
<?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
<form method="post"><?= csrf_field() ?>
<label for="u">Username</label><input id="u" name="username" required>
<label for="p">Password</label><input id="p" type="password" name="password" required>
<button class="btn" type="submit">Log in</button></form>
<p><a href="../index.php">Back to website</a></p></div></body></html>
