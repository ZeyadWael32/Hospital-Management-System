<?php
$role = $_SESSION['role'] ?? '';
?>

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
      // Show modal-specific profile errors
      if (!empty($profile_errors)) {
        foreach ($profile_errors as $error) {
          echo "<div class='alert alert-danger'>$error</div>";
        }
      }
      ?>
            <div class="mb-3">
              <label for="<?= $role ?>-name" class="col-form-label">Name:</label>
              <input type="text" class="form-control" id="<?= $role ?>-name" name="name" value="<?= htmlspecialchars($info['name']) ?>">
            </div>
            <div class="mb-3">
              <label for="<?= $role ?>-email" class="col-form-label">Email:</label>
              <input type="email" class="form-control" id="<?= $role ?>-email" name="email" value="<?= htmlspecialchars($info['email']) ?>">
            </div>
            <?php if ($role === 'doctor'): ?>
            <div class="mb-3">
              <label for="<?= $role ?>-phone" class="col-form-label">Phone:</label>
              <input type="text" class="form-control" id="<?= $role ?>-phone" name="phone" value="<?= htmlspecialchars($info['phone']) ?>">
            </div>
            <?php elseif ($role === 'patient'): ?>
            <div class="mb-3">
              <label for="<?= $role ?>-phone" class="col-form-label">Phone:</label>
              <input type="text" class="form-control" id="<?= $role ?>-phone" name="phone" value="<?= htmlspecialchars($info['phone']) ?>">
            </div>
            <div class="mb-3">
              <label for="<?= $role ?>-dob" class="col-form-label">Date of Birth:</label>
              <input type="date" class="form-control" id="<?= $role ?>-dob" name="dob" value="<?= htmlspecialchars($info['dob']) ?>">
            </div>
            <div class="mb-3">
              <label for="<?= $role ?>-address" class="col-form-label">Address:</label>
              <textarea class="form-control" id="<?= $role ?>-address" name="address"><?= htmlspecialchars($info['address']) ?></textarea>
            </div>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="update_profile">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
