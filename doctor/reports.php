<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../functions/doctor.php';

require_login();
required_role(['doctor','admin']);

$doctor_id = get_doctor_id($conn, $_SESSION['user_id']);

$title = "Reports";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
// header already includes navbar for non-auth pages
?>

<main class="container reports">
    <h2>Reports</h2>
    <button class="btn btn-primary mb-3" onclick="window.print()">Generate Report</button>

    <div class="table-responsive-custom">
    <table class="table-custom table table-striped table-bordered">
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th>Diagnosis</th>
                <th>Treatment</th>
                <th>Date Created</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $reports = get_doctor_reports($conn, $doctor_id);
            foreach ($reports as $report): ?>
                <tr>
                    <td><?= htmlspecialchars($report['report_id']) ?></td>
                    <td><?= htmlspecialchars($report['patient_name']) ?></td>
                    <td><?= htmlspecialchars($report['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($report['diagnosis']) ?></td>
                    <td><?= htmlspecialchars($report['treatment']) ?></td>
                    <td><?= htmlspecialchars($report['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>    
    </table>
    </div>
</main>