<?php
session_start();
require_once('../../../database/connection.php');


if (isset($_POST['currentPassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $verifiedUID = $_SESSION['userid']; // Assuming you store the user ID in the session

    // Prepare a select statement
    $sql = "SELECT password FROM users WHERE userid = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $verifiedUID);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if userid exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $hashedPassword);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($currentPassword, $hashedPassword)) {
                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
                    }
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No such user found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error executing query.']);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No password provided.']);
}

// Close connection
mysqli_close($link);
