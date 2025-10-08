<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ .'/../functions/patient.php';

require_login();
required_role(['patient','admin']);

$records = get_all_medical_records($conn);

$title = "Medical Records";
include __DIR__ . '/../includes/header.php';
?>

<main class="admin-medical-records">
    <div class="container card shadow p-3 mb-5 bg-body rounded">
        <div class="text-center h2 mb-4">
           All Medical Records
        </div>
        <?php if (count($records) === 0): ?>
            <p class="text-center">No medical records found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="container table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Record ID</th>
                            <th scope="col">Patient Name</th>
                            <th scope="col">Doctor Name</th>
                            <th scope="col">Appointment Date</th>
                            <th scope="col">Diagnosis</th>
                            <th scope="col">Treatment</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record['id']) ?></td>
                                <td><?= htmlspecialchars($record['patient_name']) ?></td>
                                <td><?= htmlspecialchars($record['doctor_name']) ?></td>
                                <td><?= htmlspecialchars($record['appointment_date']) ?></td>
                                <td><?= htmlspecialchars($record['diagnosis']) ?></td>
                                <td><?= htmlspecialchars($record['treatment']) ?></td>
                                <td><?= htmlspecialchars($record['record_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>