<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../functions/patient.php';

require_login();
required_role(['patient','doctor','admin']);

$title = 'Patient Profile';
include __DIR__ . '/../includes/header.php';

$info = get_patient_info($conn, $_SESSION['user_id']);
?>

<main class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center">My Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($info['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($info['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($info['phone']) ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($info['dob']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($info['address']) ?></p>

        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>