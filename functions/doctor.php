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
?>