<?php
session_start();
require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([trim($_POST['username'])]);
    if ($check->fetch()) {
        $error = "Username already taken.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, address, age, username, password)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            trim($_POST['first_name']), trim($_POST['last_name']), trim($_POST['address']),
            $_POST['age'] !== '' ? (int)$_POST['age'] : null,
            trim($_POST['username']), password_hash($_POST['password'], PASSWORD_DEFAULT)
        ]);
        header("Location: login.php?registered=1");
        exit;
    }
}
$title = 'Register';
include 'header.php';
?>
<div class="card auth-box">
  <h2>Register</h2>
  <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <form method="POST" class="stack">
    <input name="first_name" placeholder="First name" required>
    <input name="last_name" placeholder="Last name" required>
    <input name="address" placeholder="Address">
    <input type="number" name="age" placeholder="Age" min="1">
    <input name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required minlength="6">
    <button>Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Login</a></p>
</div>
<?php include 'footer.php'; ?>
