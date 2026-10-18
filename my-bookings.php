<?php
require 'config.php';
$u = current_user();
if (!$u) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $pdo->prepare("UPDATE reservations SET status='cancelled' WHERE id=? AND user_id=? AND status<>'cancelled'")
        ->execute([(int)($_POST['id'] ?? 0), $u['id']]);
    header('Location: my-bookings.php'); exit;
}
$s = $pdo->prepare('SELECT * FROM reservations WHERE user_id=? ORDER BY res_date DESC, res_time DESC');
$s->execute([$u['id']]); $rows = $s->fetchAll();
$title = 'My bookings – Angeethi';
include 'includes/header.php'; ?>
<main class="authwrap"><div class="mybox">
<h2>My bookings</h2>
<p class="lead">Signed in as <?= e($u['name']) ?> (<?= e($u['email']) ?>)</p>
<?php if (isset($_GET['booked'])): ?><p class="okbox">Booking requested. We will call you to confirm.</p><?php endif; ?>
<?php if (!$rows): ?><p>You have no bookings yet. <a href="index.php#book">Book a table</a></p><?php endif; ?>
<?php foreach ($rows as $r): ?>
<div class="bk">
  <div><b><?= e(date('D, d M Y', strtotime($r['res_date']))) ?> · <?= e(substr($r['res_time'], 0, 5)) ?></b><br><?= (int)$r['guests'] ?> guest<?= $r['guests'] > 1 ? 's' : '' ?> · <?= e($r['name']) ?></div>
  <span class="st <?= e($r['status']) ?>"><?= e(ucfirst($r['status'])) ?></span>
  <?php if ($r['status'] !== 'cancelled' && $r['res_date'] >= date('Y-m-d')): ?>
  <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn sm dark" onclick="return confirm('Cancel this booking?')">Cancel</button></form>
  <?php endif; ?>
</div>
<?php endforeach; ?>
<p><a class="btn dark" href="index.php#book">New booking</a></p>
</div></main>
<?php include 'includes/footer.php'; ?>
