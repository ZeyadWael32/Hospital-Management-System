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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1"/>
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
        
    <div class="dashboard-grid">
        <div class="card appointments-card">
            <div class="card-header">
                <h2 class="card-title">Today's Appointments</h2>
                <span class="card-action">View All →</span>
            </div>
            <div class="appointments-list">
                <div class="appointment-item">
                    <div class="appointment-info">
                        <h4>John Smith</h4>
                        <p>General Checkup</p>
                    </div>
                    <div class="appointment-time">
                        <div class="time">09:00 AM</div>
                        <div class="department">Cardiology</div>
                    </div>
                </div>
                <div class="appointment-item">
                    <div class="appointment-info">
                        <h4>Sarah Johnson</h4>
                        <p>Follow-up Consultation</p>
                    </div>
                    <div class="appointment-time">
                        <div class="time">10:30 AM</div>
                        <div class="department">Neurology</div>
                    </div>
                </div>
                <div class="appointment-item">
                    <div class="appointment-info">
                        <h4>Michael Brown</h4>
                        <p>Lab Results Review</p>
                    </div>
                    <div class="appointment-time">
                        <div class="time">02:00 PM</div>
                        <div class="department">Internal Medicine</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card quick-actions-card">
            <div class="card-header">
                <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="quick-actions">
                <button class="action-btn">➕ Add New Patient</button>
                <button class="action-btn">📅 Schedule Appointment</button>
                <button class="action-btn secondary">📊 View Reports</button>
                <button class="action-btn secondary">💊 Manage Prescriptions</button>
            </div>
        </div>

        <!-- Recent Activity (full width) -->
        <div class="card activity-card">
            <div class="card-header">
                <h2 class="card-title">Recent Activity</h2>
                <span class="card-action">View All →</span>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon blue">📝</div>
                    <div class="activity-content">
                        <h4>New patient registered</h4>
                        <p>Emma Wilson was added to the system • 15 minutes ago</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon green">✅</div>
                    <div class="activity-content">
                        <h4>Appointment completed</h4>
                        <p>Dr. Smith completed checkup with James Lee • 1 hour ago</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon orange">🔔</div>
                    <div class="activity-content">
                        <h4>Lab results available</h4>
                        <p>Blood test results for Patient #2547 are ready • 2 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>