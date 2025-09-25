<?php
$title = "Home";
include __DIR__ . '/includes/header.php';
?>

<main class="home-page">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2">
            Welcome to the Hospital Management System
        </div>
        <p class="text-center">Please <a href="pages/login.php">Login</a> or <a href="pages/register.php">Register</a> to continue.</p>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>