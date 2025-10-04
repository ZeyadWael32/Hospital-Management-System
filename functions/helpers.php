<?php
function get_first_name($full_name) {
    $parts = explode(' ', trim($full_name));
    if ($parts[0] === 'Dr.') {
        return $parts[1];
    }
        return $parts[0];
}
function get_status_badge(string $status, string $datetime): string {
    $now = new DateTime();
    $appt_time = new DateTime($datetime);

    switch ($status) {
        case 'pending':
            $badgeClass = ($appt_time < $now) ? 'warning' : 'info';
            break;
        case 'approved':
            $badgeClass = 'success';
            break;
        case 'cancelled':
            $badgeClass = 'danger';
            break;
        case 'completed':
            $badgeClass = 'primary';
            break;
        default:
            $badgeClass = 'secondary';
    }

    return "<span class='badge bg-{$badgeClass}'>" . htmlspecialchars(ucfirst($status)) . "</span>";
}


function validate_appointment_datetime($datetime) {
    $now = new DateTime();
    $selected = new DateTime($datetime);
    if ($selected < $now) {
        return false;
    }
    return true;
}
?>