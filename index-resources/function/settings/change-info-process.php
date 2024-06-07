<?php
session_start();
require_once('../../../database/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'], $_POST['email'], $_POST['phone'], $_POST['address'])) {
        $user_id = $_POST['user_id'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // Fetch existing email and phone number for the user from user_info table
        $query = "SELECT email, phone_number FROM user_info WHERE user_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $existing_email = $row['email'];
        $existing_phone = $row['phone_number'];

        // Check for duplicate email
        if ($email !== $existing_email) {
            $query = "SELECT * FROM user_info WHERE email = ?";
            $stmt = $link->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $duplicate_count = $stmt->num_rows;

            if ($duplicate_count > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Email not available']);
                exit;
            }
        }

        // Check for duplicate phone number
        if ($phone !== $existing_phone) {
            $query = "SELECT * FROM user_info WHERE phone_number = ?";
            $stmt = $link->prepare($query);
            $stmt->bind_param("s", $phone);
            $stmt->execute();
            $stmt->store_result();
            $duplicate_count = $stmt->num_rows;

            if ($duplicate_count > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Phone number not available']);
                exit;
            }
        }

        // Update user_info table
        $query = "UPDATE user_info SET email = ?, phone_number = ?, address = ? WHERE user_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("sssi", $email, $phone, $address, $user_id);
        $stmt->execute();

        // Update users table
        $query = "UPDATE users SET email = ? WHERE userid = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("ss", $email, $user_id);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Information updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Incomplete form data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
