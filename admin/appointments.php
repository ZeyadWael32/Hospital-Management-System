<?php 
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/admin.php';
require_once __DIR__ .'/../functions/helpers.php';

require_login();
required_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $appointment_id = $_POST['appointment_id'] ?? '';
    if (!$appointment_id || !is_numeric($appointment_id) || intval($appointment_id) <= 0) {
        header('Location: appointments.php?error=1'); // invalid appointment ID
        exit;
    } else {
        if (isset($_POST['approve_appointment'])) {
            if (update_appointment_status($conn, $appointment_id, 'approved')) {
                header('Location: appointments.php?success=1'); // approved successfully
                exit;
            } else {
                header('Location: appointments.php?error=2'); // approve failed / not found
                exit;
            }
        }
        if (isset($_POST['reject_appointment'])) {
            if (update_appointment_status($conn, $appointment_id, 'rejected')) {
                header('Location: appointments.php?success=2'); // rejected successfully
                exit;
            } else {
                header('Location: appointments.php?error=3'); // reject failed / not found
                exit;
            }
        }
        if (isset($_POST['complete_appointment'])) {
            if (update_appointment_status($conn, $appointment_id, 'completed')) {
                header('Location: appointments.php?success=3'); // completed successfully
                exit;
            } else {
                header('Location: appointments.php?error=4'); // complete failed / not found
                exit;
            }
        }
        if (isset($_POST['cancel_appointment'])) {
            if (update_appointment_status($conn, $appointment_id, 'canceled')) {
                header('Location: appointments.php?success=4'); // canceled successfully
                exit;
            } else {
                header('Location: appointments.php?error=5'); // cancel failed / not found
                exit;
            }
        }
    }
}

$message = '';
$alertClass = 'alert-success';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1': $message = "✅ Appointment approved successfully.";break;
        case '2': $message = "✅ Appointment rejected successfully.";break;
        case '3': $message = "✅ Appointment marked as completed.";break;
        case '4': $message = "✅ Appointment canceled successfully.";break;
        default:
            $message = '';
    }
} elseif (isset($_GET['error'])) {
    $alertClass = 'alert-danger';
    switch ($_GET['error']) {
        case '1': $message = "❌ Invalid appointment ID.";break;
        case '2': $message = "❌ Failed to approve appointment. Please try again.";break;
        case '3': $message = "❌ Failed to reject appointment. Please try again.";break;
        case '4': $message = "❌ Failed to mark appointment as completed. Please try again.";break;
        case '5': $message = "❌ Failed to cancel appointment. Please try again.";break;
        default:
            $message = '';
    }
}

$title = "Manage Appointments";
include __DIR__ . '/../includes/header.php';
?>

<main class="admin-appointments">
    <?php if (!empty($message)): ?>
      <div class="alert <?= $alertClass ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <table class="container table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Patient Name</th>
                <th scope="col">Doctor Name</th>
                <th scope="col">Appointment Date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $appointments = get_all_appointments($conn);
            if ($appointments){
                foreach ($appointments as $appt) {
                    echo "<tr>";
                    echo "<th>" . htmlspecialchars($appt['id']) . "</th>";
                    echo "<td>" . htmlspecialchars($appt['patient_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($appt['doctor_name']) . "</td>";
                    echo "<td>" . htmlspecialchars(date('Y-m-d H:i', strtotime($appt['appointment_datetime']))) . "</td>";
                    echo "<td>" . get_status_badge($appt['status'], $appt['appointment_datetime']) . "</td>";
                    echo "<td>";
                    if ($appt['status'] === 'pending') {
                        echo "<form method ='post' style='display:inline;'>
                                <input type='hidden' name='appointment_id' value='" . htmlspecialchars($appt['id']) . "'>
                                <button type='submit' name='cancel_appointment' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to cancel this appointment?\")'>Cancel</button>
                                <button type='submit' name='reject_appointment' class='btn btn-warning btn-sm' onclick='return confirm(\"Are you sure you want to reject this appointment?\")'>Reject</button>
                                <button type='submit' name='approve_appointment' class='btn btn-success btn-sm' onclick='return confirm(\"Are you sure you want to approve this appointment?\")'>Approve</button>
                                <button type='submit' name='complete_appointment' class='btn btn-primary btn-sm' onclick='return confirm(\"Are you sure you want to mark this appointment as completed?\")'>Complete</button>
                            </form>";
                    } else {
                        echo "N/A";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='5' class='text-center'>No appointments found.</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>