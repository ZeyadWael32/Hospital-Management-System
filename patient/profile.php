<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../functions/patient.php';
require_once __DIR__ . '/../functions/profile.php';

require_login();
required_role(['patient','admin']);
    

$flash = get_and_clear_session('flash');
$open_modal = get_and_clear_session('open_modal');
$modal_errors = get_and_clear_session('modal_errors', []);
$profile_errors = $modal_errors['profile'] ?? [];
$password_errors = $modal_errors['password'] ?? [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $dob = $_POST['dob'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $errors = [];

        if (!$name) $errors[] = "❌ Name is required.";
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "❌ A valid email is required.";
        if (!$phone) $errors[] = "❌ Phone number is required.";
        if (!$dob) $errors[] = "❌ Date of Birth is required.";
        if (!$address) $errors[] = "❌ Address is required.";

        if ($errors) {
            $_SESSION['modal_errors'] = ['profile' => $errors];
            $_SESSION['flash'] = ['type' => 'error', 'message' => '❌ Failed to apply changes. Please try again.'];
            $_SESSION['open_modal'] = 'profile';
        } else {
            if (update_patient_info($conn, $name, $email, $phone, $dob, $address, $_SESSION['user_id'])) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => '✅ Profile updated successfully.'];
            } else {
                $_SESSION['modal_errors'] = ['profile' => ['❌ Failed to update profile. Please try again.']];
                $_SESSION['flash'] = ['type' => 'error', 'message' => '❌ Failed to update profile. Please try again.'];
                $_SESSION['open_modal'] = 'profile';
            }
        }
        header('Location: profile.php');
        exit;
    }

    if (isset($_POST['change_password'])) {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $errors = [];

        if (!$current || !$new || !$confirm) $errors[] = "❌ All password fields are required.";
        elseif ($new !== $confirm) $errors[] = "❌ New passwords do not match.";

        if ($errors) {
            $_SESSION['modal_errors'] = ['password' => $errors];
            $_SESSION['flash'] = ['type' => 'error', 'message' => '❌ Failed to change password. Please try again.'];
            $_SESSION['open_modal'] = 'password';
        } else {
            if (change_password($conn, $_SESSION['user_id'], $current, $new)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => '✅ Password changed successfully.'];
            } else {
                $_SESSION['modal_errors'] = ['password' => ['❌ Current password is incorrect or failed to change password.']];
                $_SESSION['flash'] = ['type' => 'error', 'message' => '❌ Failed to change password. Please try again.'];
                $_SESSION['open_modal'] = 'password';
            }
        }
        header('Location: profile.php');
        exit;
    }
}

$info = get_patient_info($conn, $_SESSION['user_id']);
$title = 'Patient Profile';
include __DIR__ . '/../includes/header.php';
?>

<main class="container mt-5">
    <div class="card shadow p-4">
        <?php if ($flash): ?>
            <div class="alert alert-<?= ($flash['type'] === 'success') ? 'success' : 'danger' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>
        <h2 class="text-center">My Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($_POST['name'] ?? $info['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($_POST['email'] ?? $info['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($_POST['phone'] ?? $info['phone']) ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($_POST['dob'] ?? $info['dob']) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars(calculate_age($_POST['dob'] ?? $info['dob'])) ?> years</p>
        <p><strong>Address:</strong> <?= htmlspecialchars($_POST['address'] ?? $info['address']) ?></p>

        <?php 
            include __DIR__ . '/../includes/edit_profile_modal.php';
            include __DIR__ . '/../includes/change_password_modal.php'; 
        ?>
        <script>
            window.showProfileModal = <?= ($open_modal === 'profile') ? 'true' : 'false' ?>;
            window.showPasswordModal = <?= ($open_modal === 'password') ? 'true' : 'false' ?>;
        </script>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
