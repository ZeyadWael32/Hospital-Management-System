<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/doctor.php';

require_login();
required_role(['doctor','admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_record'])) {
        $appointment_id = intval($_POST['appointment_id'] ?? 0);
        $diagnosis = trim($_POST['diagnosis'] ?? '');
        $treatment = trim($_POST['treatment'] ?? '');

        if (!$appointment_id) {
            header('Location: medical_records.php?error=1');
            exit;
        }

        // lookup appointment and validate it belongs to this doctor
        $appointment = get_appointment_by_id($conn, $appointment_id);
        if (!$appointment) {
            header('Location: medical_records.php?error=1');
            exit;
        }
        $doctor_user_id = $_SESSION['user_id'];
        $doctor_id = get_doctor_id($conn, $doctor_user_id);
        if (intval($appointment['doctor_id']) !== intval($doctor_id)) {
            // appointment doesn't belong to this doctor
            header('Location: medical_records.php?error=1');
            exit;
        }

        $patient_id = $appointment['patient_id'];

        if (add_medical_record($conn, $patient_id, $doctor_id, $appointment_id, $diagnosis, $treatment, date('Y-m-d H:i:s'))) {
            // mark the appointment as completed so the record shows up in completed records
            update_appointment_status($conn, $appointment_id, 'completed');
            header('Location: medical_records.php?success=1'); // added successfully
            exit;
        } else {
            header('Location: medical_records.php?error=1'); // failed to add
            exit;
        }       
    }
}

$message = '';
$alertClass = 'alert-success';

// Determine doctor_id and load appointments and completed records for this doctor
$doctor_user_id = $_SESSION['user_id'];
$doctor_id = get_doctor_id($conn, $doctor_user_id);
$appointments = [];
$completed_records = [];
if ($doctor_id) {
    $appointments = get_appointments_for_doctor($conn, $doctor_id);
    $completed_records = get_completed_medical_records_for_doctor($conn, $doctor_id);
}

    if (isset($_GET['success'])) {
        switch ($_GET['success']) {
            case '1': $message = "✅ Medical record added successfully."; break;
        }
    } elseif (isset($_GET['error'])) {
        $alertClass = 'alert-danger';
        switch ($_GET['error']) {
            case '1': $message = "❌ Failed to add medical record. Please try again."; break;
        }
    }

$title = 'Medical Records';
include __DIR__ . '/../includes/header.php';
?>

<main class="doctor-medical-records">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2">
            Add Medical Record
        </div>
        <?php if ($message): ?>
            <div class="alert <?= htmlspecialchars($alertClass ?? 'alert-info') ?> text-center">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="appointment" class="form-label">Patient / Appointment</label>
                <select name="appointment_id" id="appointment" class="form-select" required>
                    <option value="" disabled selected>Select a patient & appointment</option>
                    <?php foreach ($appointments as $appt): ?>
                        <option value="<?= htmlspecialchars($appt['appointment_id']) ?>">
                            <?= htmlspecialchars($appt['patient_name'] . ' — ' . $appt['appointment_datetime']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Diagnosis</label>
                <textarea class="form-control" name="diagnosis" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Treatment</label>
                <textarea class="form-control" name="treatment" required></textarea>
            </div>

            <button type="submit" name="add_record" class="btn btn-primary">Add Record</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>

        <hr class="my-4">

        <div class="card mt-3 p-3">
            <h4 class="mb-3">Completed Medical Records</h4>
            <table class="table table-striped table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Patient</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($completed_records)): ?>
                        <?php foreach ($completed_records as $rec): ?>
                            <tr>
                                <td><?= htmlspecialchars($rec['record_id']) ?></td>
                                <td><?= htmlspecialchars($rec['patient_name']) ?></td>
                                <td><?= nl2br(htmlspecialchars($rec['diagnosis'])) ?></td>
                                <td><?= nl2br(htmlspecialchars($rec['treatment'])) ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($rec['record_date']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No completed records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>