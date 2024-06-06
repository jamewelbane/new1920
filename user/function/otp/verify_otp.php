<?php
session_start();
require_once('../../../database/connection.php');
require('../user-function.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["otp"])) {
    $enteredOTP = $_POST["otp"];
    $expectedOTP = isset($_SESSION['signup_otp']) ? strval($_SESSION['signup_otp']) : null;

    $fullname = $_POST["fullname"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $userid = $_POST["userid"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($expectedOTP)) {
        echo "emptyOTP";
        session_destroy();
        exit;
    }
    if (empty($username)) {
        echo "emptyUsername";
        session_destroy();
        exit;
    }
    if (empty($email)) {
        echo "emptyEmail";
        session_destroy();
        exit;
    }
    if (empty($password)) {
        echo "emptyPassword";
        session_destroy();
        exit;
    }
    if (empty($fullname)) {
        echo "emptyFullname";
        session_destroy();
        exit;
    }
    if (empty($phone)) {
        echo "emptyPhone";
        session_destroy();
        exit;
    }
    if (empty($address)) {
        echo "emptyAddress";
        session_destroy();
        exit;
    }
    if (isUsernameExists($link, $username)) {
        echo "existUsername";
        session_destroy();
        exit;
    }
    if (isEmailExists($link, $email)) {
        echo "existEmail";
        session_destroy();
        exit;
    }
    if (isPhoneExists($link, $phone)) {
        echo "existPhone";
        session_destroy();
        exit;
    }

    if ($enteredOTP == $expectedOTP) {
        // OTP verification successful

        // Save to database
        $queryUsers = "INSERT INTO users (userid, username, email, password) VALUES (?, ?, ?, ?)";
        $stmtUsers = mysqli_prepare($link, $queryUsers);

        $isUnique = false;
        while (!$isUnique) {
            mysqli_stmt_bind_param($stmtUsers, "ssss", $userid, $username, $email, $password);

            try {
                $isUnique = mysqli_stmt_execute($stmtUsers);
                if ($isUnique) {
                    $insertQuery = "INSERT INTO user_info (user_id, name, address, email, phone_number) VALUES (?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($link, $insertQuery);
                    mysqli_stmt_bind_param($stmt, "issss", $userid, $fullname, $address, $email, $phone);
                    $successInsertSetting = mysqli_stmt_execute($stmt);

                    if ($successInsertSetting) {
                        echo "success";
                        session_destroy();
                    } else {
                        echo "failure";
                    }
                }
            } catch (mysqli_sql_exception $e) {
                echo "An error occurred while registering. Please try again later.";
                break;
            }
        }
    } else {
        echo "failure";
    }
} else {
    echo "error";
}
?>
