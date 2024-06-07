<?php
session_start();
require_once('../../database/connection.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["otp"])) {
    $enteredOTP = $_POST["otp"];
    $expectedOTP = isset($_SESSION['reset_pass_otp']) ? strval($_SESSION['reset_pass_otp']) : null;

   
    $email = $_POST["email"];

    if ($enteredOTP == $expectedOTP) {
        // OTP verification successful
        echo "success";
        // Save to database
        // $queryUsers = "INSERT INTO users (userid, username, email, password) VALUES (?, ?, ?, ?)";
        // $stmtUsers = mysqli_prepare($link, $queryUsers);

        // $isUnique = false;
        // while (!$isUnique) {
        //     mysqli_stmt_bind_param($stmtUsers, "ssss", $userid, $username, $email, $password);

        //     try {
        //         $isUnique = mysqli_stmt_execute($stmtUsers);
        //         if ($isUnique) {
        //             $insertQuery = "INSERT INTO user_info (user_id, name, address, email, phone_number) VALUES (?, ?, ?, ?, ?)";
        //             $stmt = mysqli_prepare($link, $insertQuery);
        //             mysqli_stmt_bind_param($stmt, "issss", $userid, $fullname, $address, $email, $phone);
        //             $successInsertSetting = mysqli_stmt_execute($stmt);

        //             if ($successInsertSetting) {
        //                 echo "success";
        //                 session_destroy();
        //             } else {
        //                 echo "failure";
        //             }
        //         }
        //     } catch (mysqli_sql_exception $e) {
        //         echo "An error occurred while registering. Please try again later.";
        //         break;
        //     }
        // }
    } else {
        echo "failure";
    }
} else {
    echo "error";
}
?>
