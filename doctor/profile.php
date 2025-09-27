<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/doctor.php';

require_login();
required_role(['doctor','admin']);

$title = 'Doctor Profile';
include __DIR__ . '/../includes/header.php';

$info = get_doctor_info($conn, $_SESSION['user_id']);
?>

<main class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center">My Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($info['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($info['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($info['phone']) ?></p>
        <p><strong>Specialty:</strong> <?= htmlspecialchars($info['specialty']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($info['gender']) ?></p>

        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>