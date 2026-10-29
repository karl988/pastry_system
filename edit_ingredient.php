<?php
require_once 'auth.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM ingredients WHERE id = ?");
$stmt->execute([$id]);
$ing = $stmt->fetch();
if (!$ing) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE ingredients SET name = ?, quantity = ?, last_updated_by = ?, last_updated = NOW() WHERE id = ?");
    $stmt->execute([trim($_POST['name']), trim($_POST['quantity']), $_SESSION['user_id'], $id]);
    header("Location: ingredients.php?pastry_id=" . $ing['pastry_id']);
    exit;
}
$title = 'Edit Ingredient';
include 'header.php';
?>
<div class="card">
  <h2>Edit Ingredient</h2>
  <form method="POST" class="stack">
    <input type="hidden" name="id" value="<?= $ing['id'] ?>">
    <input name="name" value="<?= htmlspecialchars($ing['name']) ?>" required>
    <input name="quantity" value="<?= htmlspecialchars($ing['quantity']) ?>">
    <button>Save Changes</button>
    <a href="ingredients.php?pastry_id=<?= $ing['pastry_id'] ?>">Cancel</a>
  </form>
</div>
<?php include 'footer.php'; ?>
