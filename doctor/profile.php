<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/doctor.php';

require_login();
required_role(['doctor','admin']);

if (isset($_POST['update_profile']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']) ?? '';
    $email = strtolower(trim($_POST['email'])) ?? '';
    $phone = trim($_POST['phone']) ?? '';
    $specialty = trim($_POST['specialty']) ?? '';

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
    if (empty($specialty)) {
        $errors[] = "❌ Specialty is required.";
    }

    if (empty($errors)) {
        if (update_doctor_info($conn, $name, $email, $phone, $specialty, $_SESSION['user_id'])) {
            header('Location: profile.php?success=1');
            exit;
        } else {
            $errors[] = "❌ Failed to update profile. Please try again.";
        }
    }
}

if (isset($_GET['doctor_id']) && $_SESSION['role'] === 'admin') {
    if (isset($_GET['doctor_id']) && is_numeric($_GET['doctor_id'])) {
        $id = $_GET['doctor_id'];
    } else {
      $user_id = $_SESSION['user_id'];
    }

}

$info = get_doctor_info($conn, $_SESSION['user_id'] ?? $user_id);

$title = 'Doctor Profile';
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
        <p><strong>Specialty:</strong> <?= htmlspecialchars($info['specialty']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($info['gender']) ?></p>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>

        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                <div class="modal-header">
                  <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
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
                  <div class="mb-3">
                    <label for="doctor-name" class="col-form-label">Name:</label>
                    <input type="text" class="form-control" id="doctor-name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $info['name']) ?>">
                  </div>
                  <div class="mb-3">
                    <label for="doctor-email" class="col-form-label">Email:</label>
                    <input type="email" class="form-control" id="doctor-email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $info['email']) ?>">
                  </div>
                  <div class="mb-3">
                    <label for="doctor-phone" class="col-form-label">Phone:</label>
                    <input type="text" class="form-control" id="doctor-phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $info['phone']) ?>">
                  </div>
                  <div class="mb-3">
                    <label for="doctor-specialty" class="col-form-label">Specialty:</label>
                    <input type="text" class="form-control" id="doctor-specialty" name="specialty" value="<?= htmlspecialchars($_POST['specialty'] ?? $info['specialty']) ?>">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" name="update_profile">Save changes</button>
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