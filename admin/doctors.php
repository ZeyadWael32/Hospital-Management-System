<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/admin.php';

require_login();
required_role(['admin']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_doctor'])) {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? 'N/A';
        $specialty = trim($_POST['specialty'] ?? 'N/A');
        $phone = trim($_POST['phone'] ?? 'N/A');

        if (!$name) {
            header('Location: doctors.php?error=1'); // validation error
            exit;
        } elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: doctors.php?error=1'); // validation error
            exit;
        } elseif (!$password || strlen($password) < 6) {
            header('Location: doctors.php?error=1'); // validation error
            exit;
        } else {

        if (add_doctor($conn, $name, $email, $password, $specialty, $phone)) {
            header('Location: doctors.php?success=1'); // added successfully
            exit;
        } else {
            header('Location: doctors.php?error=2'); // add failed (e.g. duplicate email)
            exit;
        }
    }
}

    if (isset($_POST['delete_doctor'])) {
        $doctor_id = $_POST['doctor_id'] ?? '';

        if (!$doctor_id || !is_numeric($doctor_id) || intval($doctor_id) <= 0) {
            header('Location: doctors.php?error=3'); // invalid doctor ID
            exit;
        }

        if (delete_doctor($conn, $doctor_id)) {
            header('Location: doctors.php?success=2'); // deleted successfully
            exit;
        } else {
            header('Location: doctors.php?error=4'); // delete failed / not found
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $message = '';
    $alertClass = 'alert-success';

    if (isset($_GET['success'])) {
        switch ($_GET['success']) {
            case '1': $message = '✅ Doctor added successfully.'; break;
            case '2': $message = '✅ Doctor deleted successfully.'; break;
            default: $message = '';
        }
    } elseif (isset($_GET['error'])) {
        $alertClass = 'alert-danger';
        switch ($_GET['error']) {
            case '1': $message = '❌ Validation error. Please check the input fields.'; break;
            case '2': $message = '❌ Failed to add doctor. Please try again.'; break;
            case '3': $message = '❌ Invalid doctor ID.'; break;
            case '4': $message = '❌ Failed to delete doctor. Please try again.'; break;
            default: $message = '';
        }
    }
}

$title = "Manage Doctors";
include __DIR__ . '/../includes/header.php';
?>

<main class="admin-doctors">
  <div class="container card shadow p-3 mb-5 bg-body rounded">
    <div class="text-center h2 mb-4">
        👨‍⚕️ Add Doctor
    </div>

    <?php if ($message): ?>
        <div class="alert <?= htmlspecialchars($alertClass ?? 'alert-info') ?> text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="row g-3 align-items-start">
            <div class="col-md-6">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" placeholder="Enter name.." required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email.." required>
            </div>
            <div class="col-md-4">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password.." required aria-describedby="passwordHelp">
                <div id="passwordHelp" class="form-text">Password must be at least 6 characters long.</div>
            </div>
            <div class="col-md-4">
                <label for="specialty" class="form-label">Specialty:</label>
                <input type="text" name="specialty" class="form-control" placeholder="Enter specialty..">
            </div>
            <div class="col-md-4">
                <label for="phone" class="form-label">Phone:</label>
                <input type="text" name="phone" class="form-control" placeholder="Enter phone..">
            </div>
            <div class="col-md-6">
                <button type="submit" name="add_doctor" class="btn btn-success w-100">Add Doctor</button>
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
                <th>Specialty</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $doctors = get_all_doctors($conn);
            if ($doctors) {
                foreach ($doctors as $doctor) {
                    echo "<tr>";
                        echo "<td>" . htmlspecialchars($doctor['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($doctor['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($doctor['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($doctor['specialty']) . "</td>";
                        echo "<td>" . htmlspecialchars($doctor['phone']) . "</td>";
                        echo "<td>" . "<form method='post' style='display:inline;'>
                                        <input type='hidden' name='doctor_id' value='" . htmlspecialchars($doctor['id']) . "'>
                                        <button type='submit' name='delete_doctor' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this doctor?\")'>Delete</button>
                                       </form>" . "</td>";
                        echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No doctors found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>