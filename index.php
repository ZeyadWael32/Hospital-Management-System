<?php
require_once __DIR__ . '/includes/init.php';

$title = "Home";
include __DIR__ . '/includes/header.php';
?>

<main class="home-page">
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">Navbar</a>
    <div>
    <?php if (check_login()): ?>
        <a class="btn btn-danger" href="auth/logout.php" role="button">Logout</a>
    <?php else: ?>
        <a class="btn btn-primary" href="auth/login.php" role="button">Login</a>
        <a class="btn btn-secondary" href="auth/register.php" role="button">Register</a>
    <?php endif; ?>
    </div>
  </div>
</nav>
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2">
            Welcome to Hospital Management System
        </div>
        <p class="text-center">Please <a href="auth/login.php">Login</a> or <a href="auth/register.php">Register</a> to continue.</p>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>