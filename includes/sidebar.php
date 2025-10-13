<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<nav class="sidebar expanded" id="sidebar" aria-label="Main sidebar">
    
    <!-- Dashboard -->
    <a href="dashboard.php" class="nav-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>" role="link" <?= $current_page === 'dashboard.php' ? 'aria-current="page"' : '' ?> >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
        </svg>
        <span>Dashboard</span>
    </a>
    
    <!-- Appointments -->
    <a href="appointments.php" class="nav-item <?= $current_page === 'appointments.php' ? 'active' : '' ?>" role="link" <?= $current_page === 'appointments.php' ? 'aria-current="page"' : '' ?> >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-9 4h9m-9 4h6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>Appointments</span>
    </a>
    
    <!-- Medical Records -->
    <a href="medical_records.php" class="nav-item <?= $current_page === 'medical_records.php' ? 'active' : '' ?>" role="link" <?= $current_page === 'medical_records.php' ? 'aria-current="page"' : '' ?> >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2z" />
        </svg>
        <span>Medical Records</span>
    </a>

    <a href="reports.php" class="nav-item <?= $current_page === 'reports.php' ? 'active' : '' ?>" role="link" <?= $current_page === 'reports.php' ? 'aria-current="page"' : '' ?> >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h10M7 16h6M4 6h10l4 4v8a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z" />
        </svg>
        <span>Reports</span>
    </a>
    
    <!-- Profile -->
    <a href="profile.php" class="nav-item <?= $current_page === 'profile.php' ? 'active' : '' ?>" role="link" <?= $current_page === 'profile.php' ? 'aria-current="page"' : '' ?> >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11c1.657 0 3 1.343 3 3v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-1c0-1.657 1.343-3 3-3h8z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7a3 3 0 100-6 3 3 0 000 6z" />
        </svg>
        <span>Profile</span>
    </a>

    <!-- Logout -->
    <a href="../auth/logout.php" class="nav-item <?= $current_page === 'logout.php' ? 'active' : '' ?>" role="link" <?= $current_page === 'logout.php' ? 'aria-current="page"' : '' ?> >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
        </svg>
        <span>Logout</span>
    </a>

    <!-- Doctor Info -->
    <div class="user-profile" role="button" tabindex="0" aria-label="Open profile">
        <img src="../uploads/<?php echo htmlspecialchars($_SESSION['doctor_image'] ?? 'default.png', ENT_QUOTES); ?>" alt="Doctor profile picture">
        <span><?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></span>
    </div>
</nav>
