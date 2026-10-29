<?php
require_once 'auth.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE pastries SET name = ?, price = ?, last_updated_by = ?, last_updated = NOW() WHERE id = ?");
    $stmt->execute([trim($_POST['name']), $_POST['price'], $_SESSION['user_id'], $id]);
    header("Location: index.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM pastries WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { header("Location: index.php"); exit; }

$title = 'Edit Pastry';
include 'header.php';
?>
<div class="card">
  <h2>Edit Pastry</h2>
  <form method="POST" class="stack">
    <input type="hidden" name="id" value="<?= $p['id'] ?>">
    <input name="name" value="<?= htmlspecialchars($p['name']) ?>" required>
    <input type="number" step="0.01" name="price" value="<?= $p['price'] ?>" required>
    <button>Save Changes</button>
    <a href="index.php">Cancel</a>
  </form>
</div>
<?php include 'footer.php'; ?>
