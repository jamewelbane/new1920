<?php
require_once '../database/connection.php';

// Retrieve email from AJAX request
$email = mysqli_real_escape_string($link, $_POST['email']);

// Prepare and execute query to check if email exists in the users table
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $query);

if(mysqli_num_rows($result) > 0){
    // Email exists
    echo 'exists';
} else {
    // Email doesn't exist
    echo 'not_exists';
}
?>
