<?php
function get_patient_info($conn, $id) {
    $sql = "
    SELECT
        u.name, u.email, u.gender,
        p.phone, p.dob, p.address
    FROM users u
    INNER JOIN patients p ON u.id = p.user_id
    WHERE u.id = ?
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result && mysqli_num_rows($result) == 1) {
                $patient = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $patient;
            } else {
                mysqli_stmt_close($stmt);
                return null; // No patient found
            }
        } else {
            mysqli_stmt_close($stmt);
            return null; // Execution failed
        }
    } else {
        return null; // Preparation failed
    }
}

function update_patient_info($conn, $name, $email, $phone, $dob, $address, $id) {
    
    $sql = "
    UPDATE users u
    INNER JOIN patients p ON u.id = p.user_id
    SET u.name = ?, u.email = ?, p.phone = ?, p.dob = ?, p.address = ?
    WHERE u.id = ?
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $phone, $dob, $address, $id);
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

/* Appointment Booking Functionality */
function book_appointment($conn, $patient_id, $doctor_id, $appointment_datetime) {
    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_datetime) VALUES (?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "iis", $patient_id, $doctor_id, $appointment_datetime);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Booking successful
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function get_patient_id($conn, $user_id) {
    $sql = "SELECT id FROM patients WHERE user_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result && mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $row['id'];
            } else {
                mysqli_stmt_close($stmt);
                return null; // No patient found
            }
        } else {
            mysqli_stmt_close($stmt);
            return null; // Execution failed
        }
    } else {
        return null; // Preparation failed
    }
}

function get_doctors($conn) {
    $sql = "
    SELECT d.id, u.name, d.specialty
    FROM doctors d
    INNER JOIN users u ON d.user_id = u.id
    ";
    $doctors = [];

    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $doctors[] = $row;
        }
        mysqli_free_result($result);
    }
    return $doctors;
}

function get_patient_appointments($conn, $patient_id) {
    $sql = "
    SELECT a.id, u.name AS doctor_name, d.specialty, a.appointment_datetime, a.status
    FROM appointments a
    INNER JOIN doctors d ON a.doctor_id = d.id
    INNER JOIN users u ON d.user_id = u.id
    WHERE a.patient_id = ?
    ORDER BY a.appointment_datetime ASC
    ";
    $appointments = [];

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $patient_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $appointments[] = $row;
            }
            mysqli_free_result($result);
        }
        mysqli_stmt_close($stmt);
    }
    return $appointments;
}
function cancel_appointment($conn, $appointment_id, $patient_id) {
    $sql = "DELETE FROM appointments WHERE id = ? AND patient_id = ? AND status = 'pending'";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $appointment_id, $patient_id);
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $affected_rows > 0; // Return true if a row was deleted
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function get_patient_medical_records($conn, $patient_id) {
    $sql = "
        SELECT mr.id, mr.diagnosis, mr.treatment, mr.record_date,
               u.name AS doctor_name,
               a.appointment_datetime
        FROM medical_records mr
        INNER JOIN appointments a ON mr.appointment_id = a.id
        INNER JOIN doctors d ON a.doctor_id = d.id
        INNER JOIN users u ON d.user_id = u.id
        WHERE mr.patient_id = ?
        ORDER BY mr.record_date ASC
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $patient_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $records;
    }
    return [];
}

?>