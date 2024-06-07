<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "1920SNKRS";


// $host = "localhost";
// $user = "u433729548_1920snkrs_user";
// $password = "@Password2024";
// $db = "u433729548_1920snkrs";

$link = mysqli_connect("$host", "$user", "$password", "$db");

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>