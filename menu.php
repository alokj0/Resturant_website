<?php
require '../config.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (isset($_POST['add'])) {
        $name = trim($_POST['name'] ?? ''); $price = (float)($_POST['price'] ?? 0);
        if ($name !== '' && $price > 0)
            $pdo->prepare('INSERT INTO menu_items (category_id,name,description,price,is_veg) VALUES (?,?,?,?,?)')
                ->execute([(int)$_POST['category_id'], $name, trim($_POST['description'] ?? ''), $price, isset($_POST['is_veg']) ? 1 : 0]);
    }
    $id = (int)($_POST['id'] ?? 0);
    if (isset($_POST['toggle'])) $pdo->prepare('UPDATE menu_items SET available = 1 - available WHERE id=?')->execute([$id]);
    if (isset($_POST['delete'])) $pdo->prepare('DELETE FROM menu_items WHERE id=?')->execute([$id]);
    header('Location: menu.php'); exit;
}
$cats = $pdo->query('SELECT * FROM categories ORDER BY sort_order')->fetchAll();
$rows = $pdo->query('SELECT m.*, c.name cat FROM menu_items m JOIN categories c ON c.id = m.category_id ORDER BY c.sort_order, m.id')->fetchAll();
include '_nav.php'; ?>
<h2>Menu items</h2>
<form method="post" class="add"><?= csrf_field() ?><input type="hidden" name="add" value="1">
<div><label>Name</label><input name="name" required></div>
<div><label>Description</label><input name="description"></div>
<div><label>Price (₹)</label><input name="price" type="number" step="1" min="1" required></div>
<div><label>Category</label><select name="category_id"><?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
<div><label><input type="checkbox" name="is_veg" checked style="width:auto"> Vegetarian</label></div>
<div><button class="btn sm">Add item</button></div></form>
<table><tr><th>Item</th><th>Category</th><th>Price</th><th>Type</th><th>Shown</th><th>Action</th></tr>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['name']) ?></td><td><?= e($r['cat']) ?></td><td>₹<?= number_format($r['price']) ?></td><td><?= $r['is_veg'] ? 'Veg' : 'Non-veg' ?></td><td><?= $r['available'] ? 'Yes' : 'Hidden' ?></td>
<td><form method="post" style="display:flex;gap:6px"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id'] ?>">
<button class="btn sm" name="toggle" value="1"><?= $r['available'] ? 'Hide' : 'Show' ?></button>
<button class="btn sm" name="delete" value="1" onclick="return confirm('Delete this item?')">Delete</button></form></td></tr>
<?php endforeach; ?>
</table></div></body></html>
