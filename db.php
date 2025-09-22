<?php
$host = "localhost";
$user = "zeyadwael11";
$password = "zezowael11";
$dbname = "hospital";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>