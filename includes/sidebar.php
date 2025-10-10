<!-- Sidebar -->
<nav class="sidebar" id="sidebar" aria-label="Main sidebar">
    <button class="toggle-btn" id="sidebarToggle" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
        <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    
    <!-- Dashboard -->
    <a href="dashboard.php" class="nav-item active" role="link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
        </svg>
        <span>Dashboard</span>
    </a>
    
    <!-- Appointments -->
    <a href="appointments.php" class="nav-item" role="link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-9 4h9m-9 4h6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>Appointments</span>
    </a>
    
    <!-- Medical Records -->
    <a href="medical_records.php" class="nav-item" role="link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2z" />
        </svg>
        <span>Medical Records</span>
    </a>
    
    
    <!-- Profile -->
    <a href="profile.php" class="nav-item" role="link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
        </svg>
        <span>Profile</span>
    </a>

    <!-- Logout -->
    <a href="../auth/logout.php" class="nav-item" role="link">
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
