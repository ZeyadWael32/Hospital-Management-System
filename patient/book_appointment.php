<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../functions/patient.php';
require_once __DIR__ . '/../functions/helpers.php';

require_login();
required_role(['patient','admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['book_appointment'])) {

        $patient_id = get_patient_id($conn, $_SESSION['user_id']);
        $doctor_id = $_POST['doctor_id'] ?? '';
        $appointment_datetime = $_POST['appointment_datetime'] ?? '';

        if (!$patient_id) {
            header('Location: book_appointment.php?error=5'); // patient not found
            exit;
        } elseif (!validate_appointment_datetime($appointment_datetime)) {
            header('Location: book_appointment.php?error=3'); // past date
            exit;
        } elseif (!$doctor_id || !is_numeric($doctor_id) || intval($doctor_id) <= 0) {
            header('Location: book_appointment.php?error=4'); // invalid doctor
            exit;
        } elseif (book_appointment($conn, $patient_id, $doctor_id, $appointment_datetime)) {
            header('Location: book_appointment.php?success=1'); // booked successfully
            exit;
        } else {
            header('Location: book_appointment.php?error=1'); // generic booking failure
            exit;
        }
    }

    if (isset($_POST['cancel_appointment'])) {

        $patient_id = get_patient_id($conn, $_SESSION['user_id']);
        $appointment_id = $_POST['appointment_id'] ?? '';

        if (!$patient_id) {
            header('Location: book_appointment.php?error=5'); // patient not found
            exit;
        } elseif (!$appointment_id || !is_numeric($appointment_id) || intval($appointment_id) <= 0) {
            header('Location: book_appointment.php?error=4'); // invalid appointment
            exit;
        } elseif (cancel_appointment($conn, $appointment_id, $patient_id)) {
            header('Location: book_appointment.php?success=2'); // cancelled successfully
            exit;
        } else {
            header('Location: book_appointment.php?error=2'); // cancel failed / not found
            exit;
        }
    }
}

$message = '';
$alertClass = 'alert-success';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case '1': $message = '✅ Your appointment has been booked and is pending approval by the doctor.'; break;
        case '2': $message = '✅ Appointment cancelled successfully.'; break;
        default: $message = '';
    }
} elseif (isset($_GET['error'])) {
    $alertClass = 'alert-danger';
    switch ($_GET['error']) {
        case '1': $message = '❌ Failed to book appointment. Please try again.'; break;
        case '2': $message = '❌ Appointment not found.'; break;
        case '3': $message = '❌ Invalid appointment date and time. Please select a future date and time.'; break;
        case '4': $message = '❌ Invalid doctor selection.'; break;
        case '5': $message = '❌ Unable to identify patient. Please log in again.'; break;
        default: $message = '';
    }
}   

$title = "Book Appointment";
include __DIR__ . '/../includes/header.php';
?>

<main class="book-appointment">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2">
            Book Appointment
        </div>
        <?php if ($message): ?>
            <div class="alert <?= htmlspecialchars($alertClass ?? 'alert-info') ?> text-center">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-2">
                <label class="form-label">Select Doctor:</label>
                <select class="form-select" name="doctor_id" required>
                    <option value="" disabled selected>Select a doctor:</option>
                    <?php
                    $doctors = get_doctors($conn);
                    foreach ($doctors as $doctor) {
                        echo "<option value='" . htmlspecialchars($doctor['id']) . "'>" . htmlspecialchars($doctor['name']) . " - " . htmlspecialchars($doctor['specialty']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">Appointment Date and Time</label>
                <input type="datetime-local" class="form-control" name="appointment_datetime" min="<?= date('Y-m-d\TH:i') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="book_appointment">Book Appointment</button>
        </form>
    </div>

    <table class="container table table-striped table-bordered">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Specialty</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $patient_id = get_patient_id($conn, $_SESSION['user_id']);
            $appointments = get_patient_appointments($conn, $patient_id);
            if ($appointments) {
                foreach ($appointments as $appt) {
                    echo "<tr>";
                        echo "<td>" . htmlspecialchars($appt['doctor_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($appt['specialty']) . "</td>";
                        echo "<td>" . htmlspecialchars(date('Y-m-d H:i', strtotime($appt['appointment_datetime']))) . "</td>";
                        echo "<td>" . get_status_badge($appt['status'], $appt['appointment_datetime']) . "</td>";
                        echo "<td>";
                        if ($appt['status'] === 'pending') {
                            echo "<form method='post' style='display:inline;'>
                                    <input type='hidden' name='appointment_id' value='" . htmlspecialchars($appt['id']) . "'>
                                    <button type='submit' name='cancel_appointment' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to cancel this appointment?\")'>Cancel</button>
                                </form>";
                        } else {
                            echo "N/A";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No appointments found.</td></tr>";
                }
                ?>
        </tbody>
    </table>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
