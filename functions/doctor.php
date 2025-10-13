<?php
function get_doctor_info($conn, $id) {
    $sql = "
    SELECT
        u.name, u.email, u.gender,
        d.specialty, d.phone
    FROM users u
    INNER JOIN doctors d ON u.id = d.user_id
    WHERE u.id = ?
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt,"i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result && mysqli_num_rows($result) == 1) {
                $doctor = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $doctor;
            } else {
                mysqli_stmt_close($stmt);
                return null; // No doctor found
            }
        } else {
            mysqli_stmt_close($stmt);
            return null; // Execution failed
        }
    } else {
        return null; // Preparation failed
    }
}

function update_doctor_info($conn, $name, $email, $phone, $speciality, $id) {

    $sql = "UPDATE users u
            INNER JOIN doctors d ON u.id = d.user_id
            SET u.name = ?, u.email = ?, d.phone = ?, d.specialty = ?
            WHERE u.id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $phone, $speciality, $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Update successful
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function get_doctor_id($conn, $user_id) {
    $sql = "SELECT id FROM doctors WHERE user_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result && mysqli_num_rows($result) == 1) {
                $doctor = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $doctor['id'];
            } else {
                mysqli_stmt_close($stmt);
                return null; // No doctor found
            }
        } else {
            mysqli_stmt_close($stmt);
            return null; // Execution failed
        }
    } else {
        return null; // Preparation failed
    }
}

function get_doctor_appointments($conn, $doctor_id) {
    $sql = "
    SELECT 
        a.id, a.appointment_datetime, a.status,
        u.name AS patient_name
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.id
    INNER JOIN users u ON p.user_id = u.id
    WHERE a.doctor_id = ?
    ORDER BY a.appointment_datetime ASC
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $appointments = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $appointments;
        } else {
            mysqli_stmt_close($stmt);
            return []; // Execution failed
        }
    } else {
        return []; // Preparation failed
    }
}

function update_appointment_status($conn, $appointment_id, $status) {
    $valid_statuses = ['approved', 'cancelled', 'completed'];
    if (!in_array($status, $valid_statuses)) {
        return false; // Invalid status
    }

    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $status, $appointment_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Update successful
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function add_medical_record($conn, $patient_id, $doctor_id, $appointment_id, $diagnosis, $treatment, $record_date) {
    $sql = "INSERT INTO medical_records (patient_id, doctor_id, appointment_id, diagnosis, treatment, record_date) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiisss", $patient_id, $doctor_id, $appointment_id, $diagnosis, $treatment, $record_date);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Insert successful
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function show_distinct_appointments($conn, $doctor_id) {
    $sql = "
    SELECT DISTINCT p.id, u.name
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.id
    INNER JOIN users u ON p.user_id = u.id
    WHERE a.doctor_id = ?
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $patients = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $patients[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $patients;
        } else {
            mysqli_stmt_close($stmt);
            return []; // Execution failed
        }
    } else {
        return []; // Preparation failed
    }
}

function get_appointments_for_doctor($conn, $doctor_id) {
    $sql = "
    SELECT a.id AS appointment_id, p.id AS patient_id, u.name AS patient_name, a.appointment_datetime, a.status
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.id
    INNER JOIN users u ON p.user_id = u.id
    WHERE a.doctor_id = ?
    ORDER BY a.appointment_datetime ASC
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $appointments = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $appointments;
        } else {
            mysqli_stmt_close($stmt);
            return [];
        }
    }
    return [];
}

function get_appointment_by_id($conn, $appointment_id) {
    $sql = "SELECT id, patient_id, doctor_id, appointment_datetime, status FROM appointments WHERE id = ? LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $appointment_id);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            mysqli_stmt_close($stmt);
            return $row ?: null;
        }
        mysqli_stmt_close($stmt);
    }
    return null;
}
function get_completed_medical_records_for_doctor($conn, $doctor_id) {
    $sql = "
    SELECT mr.id AS record_id, u.name AS patient_name, mr.diagnosis, mr.treatment, mr.record_date, a.id AS appointment_id
    FROM medical_records mr
    INNER JOIN appointments a ON mr.appointment_id = a.id
    INNER JOIN patients p ON mr.patient_id = p.id
    INNER JOIN users u ON p.user_id = u.id
    WHERE a.doctor_id = ? AND a.status = 'completed'
    ORDER BY mr.record_date DESC
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $records = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $records[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $records;
        }
        mysqli_stmt_close($stmt);
    }
    return [];
}

function get_dashboard_stats($conn, $doctor_id) {

    $stats = [
        'total_appointments' => 0,
        'upcoming_appointments' => 0,
        'total_patients' => 0,
        'active_doctors' => 0
    ];

    $total_appointments = 0;
    $upcoming_appointments = 0;
    $total_patients = 0;
    $active_doctors = 0;

    if ($stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM appointments WHERE doctor_id = ?")) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $total_appointments);
            if (mysqli_stmt_fetch($stmt)) {
                $stats['total_appointments'] = (int)$total_appointments;
            }
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);
        }
    }

    if ($stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_datetime > NOW() AND status = 'approved'")) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $upcoming_appointments);
            if (mysqli_stmt_fetch($stmt)) {
                $stats['upcoming_appointments'] = (int)$upcoming_appointments;
            }
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);
        }
    }

    if ($stmt = mysqli_prepare($conn, "SELECT COUNT(DISTINCT patient_id) FROM appointments WHERE doctor_id = ?")) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $total_patients);
            if (mysqli_stmt_fetch($stmt)) {
                $stats['total_patients'] = (int)$total_patients;
            }
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);
        }
    }

    if ($stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM doctors WHERE id IS NOT NULL")) {
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $active_doctors);
            if (mysqli_stmt_fetch($stmt)) {
                $stats['active_doctors'] = (int)$active_doctors;
            }
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);
        }
    }

    return $stats;
}

function get_doctor_reports($conn, $doctor_id) {
    $sql = "
    SELECT r.id AS report_id, u.name AS patient_name, r.diagnosis, r.treatment, r.record_date, a.id AS appointment_id
    FROM medical_records r
    INNER JOIN appointments a ON r.appointment_id = a.id
    INNER JOIN patients p ON r.patient_id = p.id
    INNER JOIN users u ON p.user_id = u.id
    WHERE a.doctor_id = ?
    ORDER BY r.record_date DESC
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $reports = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $reports[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $reports;
        }
        mysqli_stmt_close($stmt);
    }
    return [];
}
?>