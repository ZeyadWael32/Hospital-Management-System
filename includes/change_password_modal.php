<?php
$role = $_SESSION['role'] ?? '';
?>
<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>

<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <div class="modal-body">
      <?php
      // Show modal-specific password errors
      if (!empty($password_errors)) {
        foreach ($password_errors as $error) {
          echo "<div class='alert alert-danger'>$error</div>";
        }
      }
      ?>
            <div class="mb-3">
              <label for="<?= $role ?>-current-password" class="col-form-label">Current Password:</label>
              <input type="password" class="form-control" id="<?= $role ?>-current-password" name="current_password">
            </div>
            <div class="mb-3">
              <label for="<?= $role ?>-new-password" class="col-form-label">New Password:</label>
              <input type="password" class="form-control" id="<?= $role ?>-new-password" name="new_password">
            </div>
            <div class="mb-3">
              <label for="<?= $role ?>-confirm-password" class="col-form-label">Confirm New Password:</label>
              <input type="password" class="form-control" id="<?= $role ?>-confirm-password" name="confirm_password">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
        </form>
        </div>
    </div>
    </div>
</div>
