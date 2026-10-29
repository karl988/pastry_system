<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Pastry System') ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
<nav>
  <strong>Pastry System</strong>
  <span>Logged in as <?= htmlspecialchars($_SESSION['full_name']) ?>
    | <a href="logout.php">Logout</a></span>
</nav>
<?php endif; ?>
<main>
