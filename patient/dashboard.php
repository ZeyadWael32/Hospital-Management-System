<?php
require_once __DIR__ . '/../includes/init.php';

require_login();
required_role(['patient','doctor','admin']);


$title = 'Patient Dashboard';
include __DIR__ . '/../includes/header.php';



$first_name = get_first_name(ucfirst(htmlspecialchars($_SESSION['name'])));
?>

<main class="patient-dashboard">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2">
            Patient Dashboard
        </div>
        <p class="text-center">Welcome, patient <?= $first_name ?>!</p>
        <a href="profile.php" class="btn btn-primary">View Profile</a>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>