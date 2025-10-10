<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../functions/doctor.php';
require_once __DIR__ . '/../functions/helpers.php';

require_login();
required_role(['doctor','admin']);

$doctor_id = get_doctor_id($conn, $_SESSION['user_id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_appointment'])) {
        $appointment_id = $_POST['appointment_id'] ?? '';

        if (!$appointment_id || !is_numeric($appointment_id) || intval($appointment_id) <= 0) {
            header('Location: dashboard.php?error=4'); // invalid appointment
            exit;
        } elseif (update_appointment_status($conn, $appointment_id, 'approved')) {
            header('Location: dashboard.php?success=1'); // approved successfully
            exit;
        } else {
            header('Location: dashboard.php?error=1'); // generic approval failure
            exit;
        }
    }

    if (isset($_POST['reject_appointment'])) {
        $appointment_id = $_POST['appointment_id'] ?? '';

        if (!$appointment_id || !is_numeric($appointment_id) || intval($appointment_id) <= 0) {
            header('Location: dashboard.php?error=4'); // invalid appointment
            exit;
        } elseif (update_appointment_status($conn, $appointment_id, 'cancelled')) {
            header('Location: dashboard.php?success=2'); // rejected successfully
            exit;
        } else {
            header('Location: dashboard.php?error=2'); // reject failed / not found
            exit;
        }
    }
}
    if (isset($_POST['complete_appointment'])) {
        $appointment_id = $_POST['appointment_id'] ?? '';

        if (!$appointment_id || !is_numeric($appointment_id) || intval($appointment_id) <= 0) {
            header('Location: dashboard.php?error=4'); // invalid appointment
            exit;
        } elseif (update_appointment_status($conn, $appointment_id, 'completed')) {
            header('Location: dashboard.php?success=3'); // completed successfully
            exit;
        } else {
            header('Location: dashboard.php?error=3'); // complete failed / not found
            exit;
        }
    }


$message = '';
$alertClass = 'alert-success';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1': $message = '✅ Appointment approved successfully.'; break;
        case '2': $message = '✅ Appointment rejected successfully.'; break;
        case '3': $message = '✅ Appointment marked as completed successfully.'; break;
        default: $message = '';
    }
} elseif (isset($_GET['error'])) {
    $alertClass = 'alert-danger';
    switch ($_GET['error']) {
        case '1': $message = '❌ Failed to approve appointment. Please try again.'; break;
        case '2': $message = '❌ Failed to reject appointment. Please try again.'; break;
        case '3': $message = '❌ Invalid date/time for appointment.'; break;
        case '4': $message = '❌ Invalid appointment selected.'; break;
        case '5': $message = '❌ Doctor profile not found. Contact admin.'; break;
        default: $message = '❌ An unknown error occurred. Please try again.';
    }
}

$title = "Appointments";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>

<main class="doctor-appointments">
    <table class="container col-6 table table-striped table-bordered">
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Appointment Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $appointments = get_doctor_appointments($conn, $doctor_id);
            if ($appointments) {
                foreach ($appointments as $appt) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($appt['patient_name']) . "</td>";
                    echo "<td>" . htmlspecialchars(date('Y-m-d H:i', strtotime($appt['appointment_datetime']))) . "</td>";
                    echo "<td>" . get_status_badge($appt['status'], $appt['appointment_datetime']) . "</td>";
                    echo "<td>";
                    if ($appt['status'] === 'pending') {
                        echo "<form method='post' style='display:inline;'>
                                <input type='hidden' name='appointment_id' value='" . htmlspecialchars($appt['id']) . "'>
                                <button type='submit' name='approve_appointment' value='approve' class='btn btn-success btn-sm me-1'>Approve</button>
                                <button type='submit' name='reject_appointment' value='reject' class='btn btn-danger btn-sm'>Reject</button>
                              </form>";
                    } elseif ($appt['status'] === 'approved') {
                        echo "<form method='post' style='display:inline;'>
                                <input type='hidden' name='appointment_id' value='" . htmlspecialchars($appt['id']) . "'>
                                <button type='submit' name='complete_appointment' value='complete' class='btn btn-info btn-sm'>Complete</button>
                              </form>";
                    } else {
                        echo "N/A";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No appointments found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>