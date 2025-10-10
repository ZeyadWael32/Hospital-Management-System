<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../functions/doctor.php';
require_once __DIR__ . '/../functions/helpers.php';

require_login();
required_role(['doctor','admin']);

$first_name = get_first_name(ucfirst(htmlspecialchars($_SESSION['name'])));
$doctor_id = get_doctor_id($conn, $_SESSION['user_id']);
$stats = get_dashboard_stats($conn, $doctor_id);

$title = "Dashboard";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';

?>

<main class="doctor-dashboard">
    <div class="dashboard-header">
        <h1>Hospital Dashboard</h1>
        <p>Welcome back, Dr. <?= $first_name ?>! Here's your hospital overview for today.</p>
    </div>
        <div class="stats-cards">
            <div class="stat-card">
                <h3>Total Patients</h3>
                <div class="value"><?= htmlspecialchars($stats['total_patients'] ?? 0) ?></div>
                <div class="change">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    8% from last month
                </div>
            </div>
            
            <div class="stat-card">
                <h3>Total Appointments</h3>
                <div class="value"><?= htmlspecialchars($stats['total_appointments'] ?? 0) ?></div>
                <div class="change">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    5 pending confirmations
                </div>
            </div>
            
            <div class="stat-card">
                <h3>Upcoming Appointments</h3>
                <div class="value"><?= htmlspecialchars($stats['upcoming_appointments'] ?? 0) ?></div>
                <div class="change negative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                    81% occupancy rate
                </div>
            </div>
            
            <div class="stat-card">
                <h3>Active Doctors</h3>
                <div class="value"><?= htmlspecialchars($stats['active_doctors'] ?? 0) ?></div>
                <div class="change">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    On duty today
                </div>
            </div>
        </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>