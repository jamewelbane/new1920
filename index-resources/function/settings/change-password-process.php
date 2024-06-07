<?php
session_start();
require_once('../../../database/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['currentPassword'], $_POST['userpass'], $_POST['confirm_userpass'])) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['userpass'];
        $confirmPassword = $_POST['confirm_userpass'];
        $verifiedUID = $_SESSION['userid']; // Assuming you store the user ID in the session

        // Validate if the new password and confirm password match
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'New password and confirm password do not match']);
            exit;
        }

        // Fetch user's current password from the database
        $query = "SELECT password FROM users WHERE userid = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param('s', $verifiedUID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($currentPassword, $user['password'])) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update user's password in the database
            $query = "UPDATE users SET password = ? WHERE userid = ?";
            $stmt = $link->prepare($query);
            $stmt->bind_param('ss', $hashedPassword, $verifiedUID);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'Password changed successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Incomplete form data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
