<?php
require_once 'auth.php';
$uid = $_SESSION['user_id'];
$pastry_id = (int)($_GET['pastry_id'] ?? $_POST['pastry_id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM pastries WHERE id = ?");
$stmt->execute([$pastry_id]);
$pastry = $stmt->fetch();
if (!$pastry) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO ingredients (pastry_id, name, quantity, added_by, last_updated_by, last_updated)
                               VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$pastry_id, trim($_POST['name']), trim($_POST['quantity']), $uid, $uid]);
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM ingredients WHERE id = ? AND pastry_id = ?");
        $stmt->execute([(int)$_POST['id'], $pastry_id]);
    }
    header("Location: ingredients.php?pastry_id=$pastry_id");
    exit;
}

$stmt = $pdo->prepare("SELECT i.*,
        CONCAT(u1.first_name,' ',u1.last_name) AS added_by_name,
        CONCAT(u2.first_name,' ',u2.last_name) AS updated_by_name
    FROM ingredients i
    LEFT JOIN users u1 ON i.added_by = u1.id
    LEFT JOIN users u2 ON i.last_updated_by = u2.id
    WHERE i.pastry_id = ? ORDER BY i.id DESC");
$stmt->execute([$pastry_id]);
$rows = $stmt->fetchAll();

$title = 'Ingredients';
include 'header.php';
?>
<p><a href="index.php">&larr; Back to pastries</a></p>
<div class="card">
  <h2>Add Ingredient to "<?= htmlspecialchars($pastry['name']) ?>"</h2>
  <form method="POST" class="stack">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="pastry_id" value="<?= $pastry_id ?>">
    <input name="name" placeholder="Ingredient name" required>
    <input name="quantity" placeholder="Quantity (e.g. 2 cups)">
    <button>Add Ingredient</button>
  </form>
</div>

<h2>Ingredients</h2>
<table>
  <tr><th>ID</th><th>Name</th><th>Quantity</th><th>Added By</th><th>Last Updated By</th><th>Last Updated</th><th>Actions</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['name']) ?></td>
    <td><?= htmlspecialchars($r['quantity']) ?></td>
    <td><?= htmlspecialchars($r['added_by_name'] ?? 'Unknown') ?></td>
    <td><?= htmlspecialchars($r['updated_by_name'] ?? 'Unknown') ?></td>
    <td><?= $r['last_updated'] ?></td>
    <td>
      <a class="btn" href="edit_ingredient.php?id=<?= $r['id'] ?>">Edit</a>
      <form method="POST" class="inline" onsubmit="return confirm('Delete this ingredient?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="pastry_id" value="<?= $pastry_id ?>">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <button class="danger">Delete</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>
