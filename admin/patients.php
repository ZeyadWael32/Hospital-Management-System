<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/admin.php';

require_login();
required_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_patient'])) {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $dob = $_POST['dob'] ?? '';
        $address = trim($_POST['address'] ?? '');

        if (!$name) {
            header('Location: patients.php?error=1'); // validation error
            exit;
        } elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: patients.php?error=1'); // validation error
            exit;
        } elseif (!$password || strlen($password) < 6) {
            header('Location: patients.php?error=1'); // validation error
            exit;

        } else {

        if (add_patient($conn, $name, $email, $password, $phone, $dob, $address)) {
            header('Location: patients.php?success=1'); // added successfully
            exit;
        } else {
            header('Location: patients.php?error=2'); // add failed (e.g. duplicate email)
            exit;
        }
    }
}

    if (isset($_POST['delete_patient'])) {
        $patient_id = $_POST['patient_id'] ?? '';

        if (!$patient_id || !is_numeric($patient_id) || intval($patient_id) <= 0) {
            header('Location: patients.php?error=3'); // invalid patient ID
            exit;
        }

        if (delete_patient($conn, $patient_id)) {
            header('Location: patients.php?success=2'); // deleted successfully
            exit;
        } else {
            header('Location: patients.php?error=4'); // delete failed / not found
            exit;
        }
    }
}


$message = '';
$alertClass = 'alert-success';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1': $message = '✅ Patient added successfully.'; break;
        case '2': $message = '✅ Patient deleted successfully.'; break;
        default: $message = '';
    }
} elseif (isset($_GET['error'])) {
    $alertClass = 'alert-danger';
    switch ($_GET['error']) {
        case '1': $message = '❌ Validation error. Please check your input.'; break;
        case '2': $message = '❌ Failed to add patient. Email may already be in use.'; break;
        case '3': $message = '❌ Invalid patient ID.'; break;
        case '4': $message = '❌ Failed to delete patient. Patient may not exist.'; break;
        default: $message = '';
    }
}

$title = "Manage Patients";
include __DIR__ . '/../includes/header.php';
?>

<main class="admin-patients">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2">
            👥 Add Patients
        </div>
        
    <?php if ($message): ?>
        <div class="alert <?= htmlspecialchars($alertClass ?? 'alert-info') ?> text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="row g-3 align-items-start">
        <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name.." required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email.." required>
        </div>
        <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password.." required>
        </div>
        <div class="col-md-6">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number.." required>
        </div>
        <div class="col-md-6">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
        </div>
        <div class="col-md-6">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter address.." required>
        </div>
        <div class="col-md-6">
            <button type="submit" name="add_patient" class="btn btn-primary">Add Patient</button>
        </div>
      </div>
    </div>
</form>

  <table class="container table table-bordered table-striped mt-5">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>DOB</th>
        <th>Address</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
        <?php
        $patients = get_all_patients($conn);
        if ($patients) {
            foreach ($patients as $patient) {
                echo "<tr>";
                    echo "<td>" . htmlspecialchars($patient['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($patient['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($patient['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($patient['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($patient['dob']) . "</td>";
                    echo "<td>" . htmlspecialchars($patient['address']) . "</td>";
                    echo "<td>" . "<form method='post' style='display:inline;'>
                                        <input type='hidden' name='patient_id' value='" . htmlspecialchars($patient['id']) . "'>
                                        <button type='submit' name='delete_patient' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this patient?\")'>Delete</button>
                                       </form>" . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No patients found.</td></tr>";
        }
        ?>
    </tbody>
  </table>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>