<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../functions/patient.php';

require_login();
required_role(['patient','admin']);

if (isset($_POST['update_profile']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']) ?? '';
    $email = strtolower(trim($_POST['email'])) ?? '';
    $phone = trim($_POST['phone']) ?? '';
    $dob = $_POST['dob'] ?? '';
    $address = trim($_POST['address']) ?? '';

    $errors = [];

    if (empty($name)) {
        $errors[] = "❌ Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "❌ A valid email is required.";
    }
    if (empty($phone)) {
        $errors[] = "❌ Phone number is required.";
    }
    if (empty($dob)) {
        $errors[] = "❌ Date of Birth is required.";
    }
    if (empty($address)) {
        $errors[] = "❌ Address is required.";
    }

    if (empty($errors)) {
        if (update_patient_info($conn, $name, $email, $phone, $dob, $address, $_SESSION['user_id'])) {
            header('Location: profile.php?success=1');
            exit;
        } else {
            $errors[] = "❌ Failed to update profile. Please try again.";
        }
    }
}

if (isset($_GET['patient_id']) && $_SESSION['role'] === 'admin') {
    if (isset($_GET['patient_id']) && is_numeric($_GET['patient_id'])) {
        $id = $_GET['patient_id'];
    } else {
      $user_id = $_SESSION['user_id'];
    }

}

$info = get_patient_info($conn, $_SESSION['user_id']);

$title = 'Patient Profile';
include __DIR__ . '/../includes/header.php';

?>

<main class="container mt-5">
    <div class="card shadow p-4">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">✅ Profile updated successfully.</div>
            <?php endif; ?>
        <h2 class="text-center">My Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($info['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($info['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($info['phone']) ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($info['dob']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($info['address']) ?></p>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>

    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title fs-5" id="editProfileModalLabel">Edit Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <?php
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            ?>
            <form method="post">
            <div class="mb-3">
                <label for="patient-name" class="col-form-label">Name:</label>
                <input type="text" class="form-control" name="name" id="patient-name" value="<?= htmlspecialchars($_POST['name'] ?? $info['name']) ?>">
            </div>
            <div class="mb-3">
                <label for="patient-email" class="col-form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="patient-email" value="<?= htmlspecialchars($_POST['email'] ?? $info['email']) ?>">
            </div>
            <div class="mb-3">
                <label for="patient-phone" class="col-form-label">Phone:</label>
                <input type="text" class="form-control" name="phone" id="patient-phone" value="<?= htmlspecialchars($_POST['phone'] ?? $info['phone']) ?>">
            </div>
            <div class="mb-3">
                <label for="patient-dob" class="col-form-label">Date of Birth:</label>
                <input type="date" class="form-control" name="dob" id="patient-dob" value="<?= htmlspecialchars($_POST['dob'] ?? $info['dob']) ?>">
            </div>
            <div class="mb-3">
                <label for="patient-address" class="col-form-label">Address:</label>
                <textarea class="form-control" name="address" id="patient-address"><?= htmlspecialchars($_POST['address'] ?? $info['address']) ?></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="update_profile">Save Changes</button>
        </div>
        </form>
       </div>
      </div>
     </div>
   </div>
<script>
    window.showProfileModal = <?= !empty($errors) ? 'true' : 'false'; ?>;
</script>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>