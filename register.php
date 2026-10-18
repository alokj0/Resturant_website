<?php
require 'config.php';
$title = 'Sign up – Angeethi'; $errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $n = trim($_POST['name'] ?? ''); $em = strtolower(trim($_POST['email'] ?? ''));
    $p = trim($_POST['phone'] ?? ''); $pw = $_POST['password'] ?? '';
    if ($n === '' || strlen($n) > 80) $errors[] = 'Enter your name.';
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!preg_match('/^[0-9+ ]{10,15}$/', $p)) $errors[] = 'Enter a valid phone number.';
    if (strlen($pw) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($pw !== ($_POST['password2'] ?? '')) $errors[] = 'Passwords do not match.';
    if (!$errors) {
        $s = $pdo->prepare('SELECT id FROM users WHERE email=?'); $s->execute([$em]);
        if ($s->fetch()) $errors[] = 'This email is already registered. Log in instead.';
    }
    if (!$errors) {
        $pdo->prepare('INSERT INTO users (name,email,phone,password_hash) VALUES (?,?,?,?)')
            ->execute([$n, $em, $p, password_hash($pw, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        header('Location: index.php#book'); exit;
    }
}
include 'includes/header.php'; ?>
<main class="authwrap"><div class="authbox">
<h2>Create your account</h2>
<?php foreach ($errors as $er): ?><p class="err"><?= e($er) ?></p><?php endforeach; ?>
<form method="post"><?= csrf_field() ?>
<label for="n">Full name</label><input id="n" name="name" value="<?= e($_POST['name'] ?? '') ?>" required>
<label for="e">Email</label><input id="e" type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>
<label for="p">Phone</label><input id="p" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" required>
<label for="pw">Password</label><input id="pw" type="password" name="password" required>
<label for="pw2">Confirm password</label><input id="pw2" type="password" name="password2" required>
<button class="btn dark" type="submit">Sign up</button>
</form>
<p class="alt">Already have an account? <a href="login.php">Log in</a></p>
</div></main>
<?php include 'includes/footer.php'; ?>
