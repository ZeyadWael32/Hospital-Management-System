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
?>