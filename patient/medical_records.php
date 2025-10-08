<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/patient.php';

require_login();
required_role(['patient','admin']);

$patient_id = get_patient_id($conn, $_SESSION['user_id']);
$records = get_patient_medical_records($conn, $patient_id);

$title = "Medical Records";
include __DIR__ . '/../includes/header.php';
?>

<main class="patient-medical-records">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2 mb-4">
            Medical Records
        </div>
        <?php if (empty($records)): ?>
            <p class="text-center">No medical records found.</p> 
        <?php else: ?>
            <table class="container table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Created At</th>
                        <th>Appointment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $rec): ?>
                        <tr>
                            <td><?= htmlspecialchars($rec['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($rec['diagnosis']) ?></td>
                            <td><?= htmlspecialchars($rec['treatment']) ?></td>
                            <td><?= htmlspecialchars($rec['record_date']) ?></td>
                            <td><?= htmlspecialchars($rec['appointment_datetime']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>