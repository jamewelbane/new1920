<?php
session_start();
require_once('../../database/connection.php');
require('user-function.php');
require('otp/otp_mail.php');

// Define the cooldown period in seconds (e.g., 60 seconds for a 1-minute cooldown)
$cooldownPeriod = 60;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $email = $_POST["email"];

    // Check if a new OTP can be generated based on the cooldown period
    $lastOtpRequestTime = isset($_SESSION['last_otp_request_time']) ? $_SESSION['last_otp_request_time'] : 0;
    $currentTime = time();
    $timeSinceLastRequest = $currentTime - $lastOtpRequestTime;

    if ($timeSinceLastRequest < $cooldownPeriod) {
        // Cooldown period hasn't elapsed yet
        $timeLeft = $cooldownPeriod - $timeSinceLastRequest;
        echo "Please wait $timeLeft seconds before requesting a new OTP.";
        exit;
    }

    // Generate new OTP
    $newOtp = generateOTP();

    if ($newOtp !== null) {
        // Update session with new OTP and last request time
        $_SESSION['signup_otp'] = $newOtp;
        $_SESSION['last_otp_request_time'] = $currentTime;

        // Fetch user details for sending email
        $username = ''; // Fetch the username from the database if necessary

        // Send the new OTP to the user's email
        if (send_verification_email($email, $username, $newOtp)) {
            echo 'success';
        } else {
            echo 'success';
        }
    } else {
        echo 'Failed to generate a new OTP. Please try again.';
    }
} else {
    echo 'Invalid request. Please try again.';
}
?>
