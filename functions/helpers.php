<?php
function get_first_name($full_name) {
    $parts = explode(' ', trim($full_name));
    if ($parts[0] === 'Dr.') {
        return $parts[1];
    }
        return $parts[0];
}
?>