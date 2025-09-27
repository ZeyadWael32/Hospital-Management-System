<?php
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
?>