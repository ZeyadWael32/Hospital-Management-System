<?php
function get_first_name($full_name) {
    $parts = explode(' ', trim($full_name));
    return $parts[0];
}
?>