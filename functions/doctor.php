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
?>