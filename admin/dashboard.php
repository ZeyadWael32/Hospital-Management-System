<?php
require_once __DIR__ . '/../includes/init.php';

require_login();
required_role(['admin']);

$title = "Admin Dashboard";
include __DIR__ . '/../includes/header.php';

$first_name = get_first_name(ucfirst(htmlspecialchars($_SESSION["name"])));

?>
<main class="admin-dashboard">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2">
            Admin Dashboard
        </div>
        <p class="text-center">Welcome, <?= $first_name ?>!</p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="patients.php" class="btn btn-primary me-2 mb-2">
                <i class="bi bi-people-fill me-1" aria-hidden="true"></i>
                Manage Patients
            </a>
            <a href="doctors.php" class="btn btn-success me-2 mb-2">
                <i class="bi bi-person-badge-fill me-1" aria-hidden="true"></i>
                Manage Doctors
            </a>
            <a href="appointments.php" class="btn btn-warning text-dark me-2 mb-2">
                <i class="bi bi-calendar-check-fill me-1" aria-hidden="true"></i>
                Manage Appointments
            </a>
            <a href="profile.php" class="btn btn-outline-secondary mb-2">
                <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
                View Profile
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>