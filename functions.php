<?php
function email_check($conn, $email) {
    $email_check_sql = "SELECT id FROM users WHERE email = ?";
    if ($email_check_stmt = mysqli_prepare($conn, $email_check_sql)) {
        mysqli_stmt_bind_param($email_check_stmt, "s", $email);
        if (mysqli_stmt_execute($email_check_stmt)) {
            mysqli_stmt_store_result($email_check_stmt);
            $is_registered = mysqli_stmt_num_rows($email_check_stmt) > 0;
            mysqli_stmt_close($email_check_stmt);
            return $is_registered;
        } else {
            mysqli_stmt_close($email_check_stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function insert_user($conn, $name, $email, $password, $gender, $role = 'patient') {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $insert_sql = "INSERT INTO users (name, email, password, gender, role) VALUES (?, ?, ?, ?, ?)";
    if ($insert_stmt = mysqli_prepare($conn, $insert_sql)) {
        mysqli_stmt_bind_param($insert_stmt, "sssss", $name, $email, $hashed_password, $gender, $role);
        if (mysqli_stmt_execute($insert_stmt)) {
            $user_id = mysqli_insert_id($conn);
            mysqli_stmt_close($insert_stmt);
            return $user_id;
        } else {
            mysqli_stmt_close($insert_stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}
function insert_patient($conn, $user_id, $phone, $date_of_birth, $address) {
    $insert_sql = "INSERT INTO patients (user_id, phone, dob, address) VALUES (?, ?, ?, ?)";
    if ($insert_stmt = mysqli_prepare($conn, $insert_sql)) {
        mysqli_stmt_bind_param($insert_stmt, "isss", $user_id, $phone, $date_of_birth, $address);
        if (mysqli_stmt_execute($insert_stmt)) {
            mysqli_stmt_close($insert_stmt);
            return true;
        } else {
            mysqli_stmt_close($insert_stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}
?>