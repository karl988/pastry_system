<?php
require_once 'auth.php';
$uid = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO pastries (name, price, added_by, last_updated_by, last_updated)
                               VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([trim($_POST['name']), $_POST['price'], $uid, $uid]);
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM pastries WHERE id = ?");
        $stmt->execute([(int)$_POST['id']]);
    }
    header("Location: index.php");
    exit;
}

$rows = $pdo->query("SELECT p.*,
        CONCAT(u1.first_name,' ',u1.last_name) AS added_by_name,
        CONCAT(u2.first_name,' ',u2.last_name) AS updated_by_name
    FROM pastries p
    LEFT JOIN users u1 ON p.added_by = u1.id
    LEFT JOIN users u2 ON p.last_updated_by = u2.id
    ORDER BY p.id DESC")->fetchAll();

$title = 'Pastries';
include 'header.php';
?>
<div class="card">
  <h2>Add Pastry</h2>
  <form method="POST" class="stack">
    <input type="hidden" name="action" value="add">
    <input name="name" placeholder="Pastry name" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <button>Add Pastry</button>
  </form>
</div>

<h2>Pastries</h2>
<table>
  <tr><th>ID</th><th>Name</th><th>Price</th><th>Added By</th><th>Last Updated By</th><th>Last Updated</th><th>Actions</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['name']) ?></td>
    <td><?= number_format($r['price'], 2) ?></td>
    <td><?= htmlspecialchars($r['added_by_name'] ?? 'Unknown') ?></td>
    <td><?= htmlspecialchars($r['updated_by_name'] ?? 'Unknown') ?></td>
    <td><?= $r['last_updated'] ?></td>
    <td>
      <a class="btn" href="ingredients.php?pastry_id=<?= $r['id'] ?>">Ingredients</a>
      <a class="btn" href="edit_pastry.php?id=<?= $r['id'] ?>">Edit</a>
      <form method="POST" class="inline" onsubmit="return confirm('Delete this pastry and its ingredients?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <button class="danger">Delete</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>
