<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([trim($_POST['username'])]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        header("Location: index.php");
        exit;
    }
    $error = "Invalid username or password.";
}
$title = 'Login';
include 'header.php';
?>
<div class="card auth-box">
  <h2>Login</h2>
  <?php if (isset($_GET['registered'])): ?><p class="success">Registration successful. Please log in.</p><?php endif; ?>
  <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <form method="POST" class="stack">
    <input name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button>Login</button>
  </form>
  <p>No account yet? <a href="register.php">Register</a></p>
</div>
<?php include 'footer.php'; ?>
