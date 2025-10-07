<?php
/* =========================== Admin profile functions =========================== */
function get_admin_info($conn, $id) {
    $sql = "SELECT name, email, gender FROM users WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt,"i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result && mysqli_num_rows($result) == 1) {
                $admin = mysqli_fetch_assoc($result);
                mysqli_stmt_close($stmt);
                return $admin;
            } else {
                mysqli_stmt_close($stmt);
                return null; // No admin found
            }
        } else {
            mysqli_stmt_close($stmt);
            return null; // Execution failed
        }
    } else {
        return null; // Preparation failed
    }
}

function update_admin_info($conn, $name, $email, $id) {
    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $id);
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

/* =========================== Admin patient functions =========================== */
function add_patient($conn, $name, $email, $password, $phone, $dob, $address) {
    $sql_email_check = "SELECT id FROM users WHERE email = ?";
    if ($stmt_check = mysqli_prepare($conn, $sql_email_check)) {
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        if (mysqli_stmt_execute($stmt_check)) {
            $result = mysqli_stmt_get_result($stmt_check);
            if ($result && mysqli_num_rows($result) > 0) {
                // Email already exists
                mysqli_stmt_close($stmt_check);
                return false;
            }
            mysqli_stmt_close($stmt_check);
        } else {
            mysqli_stmt_close($stmt_check);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }

    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'patient')";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);
        if (mysqli_stmt_execute($stmt)) {
            $user_id = mysqli_insert_id($conn);

            mysqli_stmt_close($stmt);


            $sql_pat = "INSERT INTO patients (user_id, phone, dob, address) VALUES (?, ?, ?, ?)";
            if ($stmt_pat = mysqli_prepare($conn, $sql_pat)) {
                mysqli_stmt_bind_param($stmt_pat, "isss", $user_id, $phone, $dob, $address);
                if (mysqli_stmt_execute($stmt_pat)) {
                    mysqli_stmt_close($stmt_pat);
                    return true; // Patient added successfully
                } else {
                    mysqli_stmt_close($stmt_pat);
                    return false; // Execution failed
                }
            } else {
                return false; // Preparation failed
            }
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function get_all_patients($conn) {
    $sql = "SELECT u.id, u.name, u.email, p.phone, p.dob, p.address
            FROM users u
            INNER JOIN patients p ON u.id = p.user_id
            ORDER BY u.id DESC";

    if ($stmt = mysqli_prepare($conn, $sql)) {
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

function delete_patient($conn, $id) {
    $sql = "DELETE u, p FROM users u
            INNER JOIN patients p ON u.id = p.user_id
            WHERE u.id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Deletion successful
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

/* =========================== Admin doctor functions =========================== */

function add_doctor($conn, $name, $email, $specialty, $password, $phone) {
    $sql_email_check = "SELECT id FROM users WHERE email = ?";
    if ($stmt_check = mysqli_prepare($conn, $sql_email_check)) {
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        if (mysqli_stmt_execute($stmt_check)) {
            $result = mysqli_stmt_get_result($stmt_check);
            if ($result && mysqli_num_rows($result) > 0) {
                // Email already exists
                mysqli_stmt_close($stmt_check);
                return false;
            }
            mysqli_stmt_close($stmt_check);
        } else {
            mysqli_stmt_close($stmt_check);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }

    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'doctor')";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);
        if (mysqli_stmt_execute($stmt)) {
            $user_id = mysqli_insert_id($conn);

            mysqli_stmt_close($stmt);


            $sql_doc = "INSERT INTO doctors (user_id, specialty, phone) VALUES (?, ?, ?)";
            if ($stmt_doc = mysqli_prepare($conn, $sql_doc)) {
                mysqli_stmt_bind_param($stmt_doc, "iss", $user_id, $specialty, $phone);
                if (mysqli_stmt_execute($stmt_doc)) {
                    mysqli_stmt_close($stmt_doc);
                    return true; // Doctor added successfully
                } else {
                    mysqli_stmt_close($stmt_doc);
                    return false; // Execution failed
                }
            } else {
                return false; // Preparation failed
            }
            } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

function get_all_doctors($conn) {
    $sql = "SELECT u.id, u.name, u.email, d.specialty, d.phone
            FROM users u
            INNER JOIN doctors d ON u.id = d.user_id
            ORDER BY u.id DESC";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $doctors = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $doctors[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $doctors;
        } else {
            mysqli_stmt_close($stmt);
            return []; // Execution failed
        }
    } else {
        return []; // Preparation failed
    }
}

function delete_doctor($conn, $id) {
    $sql = "DELETE u, d FROM users u
            INNER JOIN doctors d ON u.id = d.user_id
            WHERE u.id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true; // Deletion successful
        } else {
            mysqli_stmt_close($stmt);
            return false; // Execution failed
        }
    } else {
        return false; // Preparation failed
    }
}

/* =========================== Admin appointment functions =========================== */
function get_all_appointments($conn) {
    $sql = "
    SELECT 
        a.id,
        a.appointment_datetime,
        a.status,
        u_patient.name AS patient_name,
        u_doctor.name AS doctor_name
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.id
    INNER JOIN users u_patient ON p.user_id = u_patient.id
    INNER JOIN doctors d ON a.doctor_id = d.id
    INNER JOIN users u_doctor ON d.user_id = u_doctor.id
    ORDER BY a.appointment_datetime ASC;
    ";

    if ($stmt = mysqli_prepare($conn, $sql)) {
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

function update_appointment_status($conn, $id, $status) {
    $valid_statuses = ['approved', 'rejected', 'completed', 'canceled'];
    if (!in_array($status, $valid_statuses)) {
        return false; // Invalid status
    }

    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
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