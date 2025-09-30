<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/admin.php';
require_once __DIR__ .'/../functions/profile.php';

require_login();
required_role(['admin']);

// Read and clear one-time flash and modal state
$flash = get_and_clear_session('flash');
$open_modal = get_and_clear_session('open_modal');
$modal_errors = get_and_clear_session('modal_errors', []);
$profile_errors = $modal_errors['profile'] ?? [];
$password_errors = $modal_errors['password'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

if (isset($_POST['update_profile'])) {
  $name = trim($_POST['name'] ?? '');
  $email = strtolower(trim($_POST['email'] ?? ''));

  $profile_errors = [];
  if (empty($name)) {
    $profile_errors[] = "❌ Name is required.";
  }
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $profile_errors[] = "❌ A valid email is required.";
  }

  if (!empty($profile_errors)) {
    $_SESSION['modal_errors'] = ['profile' => $profile_errors];
    $_SESSION['flash'] = ['type' => 'error', 'message' => '❌ Failed to update profile. Please try again.'];
    $_SESSION['open_modal'] = 'profile';
    header('Location: profile.php');
    exit;
  }

  if (update_admin_info($conn, $name, $email, $_SESSION['user_id'])) {
    $_SESSION['flash'] = ['type' => 'success', 'message' => '✅ Profile updated successfully.'];
    header('Location: profile.php');
    exit;
  } else {
    $_SESSION['modal_errors'] = ['profile' => ['❌ Failed to update profile. Please try again.']];
    $_SESSION['flash'] = ['type' => 'error', 'message' => '❌ Failed to update profile. Please try again.'];
    $_SESSION['open_modal'] = 'profile';
    header('Location: profile.php');
    exit;
  }
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

$info = get_admin_info($conn, $_SESSION['user_id']);

$title = 'Admin Profile';
include __DIR__ . '/../includes/header.php';
?>

<main class="container mt-5">
    <div class="card shadow p-4">
    <?php if (!empty($flash)): ?>
      <div class="alert alert-<?= ($flash['type'] === 'success') ? 'success' : 'danger' ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>
        <h2 class="text-center">My Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($info['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($info['email']) ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($info['gender']) ?></p>

        <?php
         include __DIR__ . '/../includes/edit_profile_modal.php'; 
         include __DIR__ . '/../includes/change_password_modal.php';
         ?>

  <script>
    window.showProfileModal = <?= ($open_modal === 'profile') ? 'true' : 'false'; ?>;
    window.showPasswordModal = <?= ($open_modal === 'password') ? 'true' : 'false'; ?>;
  </script>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>