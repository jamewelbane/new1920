<?php
session_start();
require_once '../../database/connection.php';

// Verify if user is logged in
if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'message' => 'You need to log in first.']);
    exit;
}

$verifiedUID = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'cancel-request') {
    // Check if a note is provided
    $reason = isset($_POST['reason']) ? $_POST['reason'] : "";

    if (!isset($_POST['order_id_input'])) {
        echo json_encode(['success' => false, 'message' => 'Failed to get order details']);
        exit;
    }

    $order_id = $_POST['order_id_input'];

    // Fetch order details
    $query = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row =  $result->fetch_assoc();

    $txn = $row['transaction_number'];

    $stmt->close();

     // Insert into orders table
     $stmt = $link->prepare("INSERT INTO cancellation_request (order_id, txn, user_id, reason) VALUES (?, ?, ?, ?)");
     $stmt->bind_param("isisi", $order_id, $txn, $verifiedUID, $reason);
     $stmt->execute();
     $stmt->close();


    echo json_encode(['success' => true, 'message' => 'You have successfully requested a cancellation for order with TXN: ' . $txn, 'transaction_number' => $txn, 'userid' => $verifiedUID]);
    $link->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
