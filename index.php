<?php
require '../config.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf(); $id = (int)($_POST['id'] ?? 0);
    if (isset($_POST['status']) && in_array($_POST['status'], ['pending', 'confirmed', 'cancelled'], true))
        $pdo->prepare('UPDATE reservations SET status=? WHERE id=?')->execute([$_POST['status'], $id]);
    if (isset($_POST['delete'])) $pdo->prepare('DELETE FROM reservations WHERE id=?')->execute([$id]);
    header('Location: index.php'); exit;
}
$counts = ['pending' => 0, 'confirmed' => 0, 'cancelled' => 0];
foreach ($pdo->query('SELECT status, COUNT(*) c FROM reservations GROUP BY status') as $r) $counts[$r['status']] = $r['c'];
$rows = $pdo->query('SELECT r.*, u.email FROM reservations r JOIN users u ON u.id = r.user_id ORDER BY r.res_date DESC, r.res_time DESC')->fetchAll();
include '_nav.php'; ?>
<h2>Bookings</h2>
<div class="stats"><?php foreach ($counts as $k => $v): ?><div class="stat"><b><?= $v ?></b><?= e(ucfirst($k)) ?></div><?php endforeach; ?><div class="stat"><b><?= (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() ?></b>Customers</div></div>
<table><tr><th>Name</th><th>Phone</th><th>Account</th><th>Date</th><th>Time</th><th>Guests</th><th>Status</th><th>Action</th></tr>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['name']) ?></td><td><?= e($r['phone']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['res_date']) ?></td><td><?= e(substr($r['res_time'], 0, 5)) ?></td><td><?= (int)$r['guests'] ?></td><td><?= e($r['status']) ?></td>
<td><form method="post" style="display:flex;gap:6px"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id'] ?>">
<button class="btn sm" name="status" value="confirmed">Confirm</button>
<button class="btn sm" name="status" value="cancelled">Cancel</button>
<button class="btn sm" name="delete" value="1" onclick="return confirm('Delete this booking?')">Delete</button></form></td></tr>
<?php endforeach; if (!$rows): ?><tr><td colspan="8">No bookings yet.</td></tr><?php endif; ?>
</table></div></body></html>
