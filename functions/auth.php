<?php
/* Register functions */
function reg_email_check($conn, $email) {
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

function user_register($conn, $name, $email, $password, $gender, $role = 'patient') {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $register_sql = "INSERT INTO users (name, email, password, gender, role) VALUES (?, ?, ?, ?, ?)";
    if ($insert_stmt = mysqli_prepare($conn, $register_sql)) {
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
function patient_register($conn, $user_id, $phone, $date_of_birth, $address) {
    $register_sql = "INSERT INTO patients (user_id, phone, dob, address) VALUES (?, ?, ?, ?)";
    if ($insert_stmt = mysqli_prepare($conn, $register_sql)) {
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
/* Login functions */
function user_login($conn, $email, $password) {
    $login_sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
    if ($login_stmt = mysqli_prepare($conn, $login_sql)) {
        mysqli_stmt_bind_param($login_stmt, "s", $email);
        if (mysqli_stmt_execute($login_stmt)) {
            mysqli_stmt_store_result($login_stmt);
            if (mysqli_stmt_num_rows($login_stmt) == 1) {
                mysqli_stmt_bind_result($login_stmt, $fetched_id, $fetched_name, $fetched_email, $fetched_hashed_password, $fetched_role);
                if (mysqli_stmt_fetch($login_stmt)) {
                    if (password_verify($password, $fetched_hashed_password)) {
                        mysqli_stmt_close($login_stmt);
                        return [
                            "id" => $fetched_id,
                            "name" => $fetched_name,
                            "email" => $fetched_email,
                            "role" => $fetched_role
                        ]; // Login successful
                    } else {
                        mysqli_stmt_close($login_stmt);
                        return false; // Invalid password
                    }
                }
            } else {
                mysqli_stmt_close($login_stmt);
                return false; // No user found
            }
        }
        mysqli_stmt_close($conn);
    }
    return false; // Preparation or execution failed
}
?>