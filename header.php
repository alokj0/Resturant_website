<?php $u = current_user(); ?><!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Angeethi – Wood-fire kitchen') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Newsreader:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head><body>
<header class="nav"><div class="navin">
<a class="logo" href="index.php">🔥 Angeethi</a>
<nav>
<a href="index.php#menu">Menu</a><a href="index.php#book">Book a table</a>
<?php if ($u): ?>
<a href="my-bookings.php">My bookings</a>
<a class="pill" href="logout.php">Log out (<?= e(explode(' ', $u['name'])[0]) ?>)</a>
<?php else: ?>
<a href="login.php">Log in</a><a class="pill" href="register.php">Sign up</a>
<?php endif; ?>
</nav></div></header>
