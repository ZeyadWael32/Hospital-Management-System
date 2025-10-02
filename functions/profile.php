<?php
function get_and_clear_session($key, $default = null) {
    $value = $_SESSION[$key] ?? $default;
    if (isset($_SESSION[$key])) unset($_SESSION[$key]);
    return $value;
}

function change_password($conn, $user_id, $current_password, $new_password) {
    // Fetch the current hashed password from the database
    $fetch_sql = "SELECT password FROM users WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $fetch_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $fetched_hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    // Verify the current password
                    if (password_verify($current_password, $fetched_hashed_password)) {
                        // Hash the new password
                        $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                        // Update the password in the database
                        $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                        if ($update_stmt = mysqli_prepare($conn, $update_sql)) {
                            mysqli_stmt_bind_param($update_stmt, "si", $new_hashed_password, $user_id);
                            if (mysqli_stmt_execute($update_stmt)) {
                                mysqli_stmt_close($update_stmt);
                                mysqli_stmt_close($stmt);
                                return true; // Password changed successfully
                            } else {
                                mysqli_stmt_close($update_stmt);
                                mysqli_stmt_close($stmt);
                                return false; // Execution failed
                            }
                        } else {
                            mysqli_stmt_close($stmt);
                            return false; // Preparation failed
                        }
                    } else {
                        mysqli_stmt_close($stmt);
                        return false; // Current password is incorrect
                    }
                }
            } else {
                mysqli_stmt_close($stmt);
                return false; // No user found
            }
        }
        mysqli_stmt_close($stmt);
    }
    return false; // Preparation or execution failed
}

function calculate_age($dob) {
    if ($dob) {
        $birthDate = strtotime($dob);
        $age = date('Y') - date('Y', $birthDate);

        if (date('md', $birthDate) > date('md', time())) {
            $age--;
        }
        return $age;
    } else {
        return null;
    }
}
?>
