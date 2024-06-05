<?php
session_start();
require_once '../../database/connection.php';

// Verify if user is logged in
if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'message' => 'You need to log in first.']);
 
    exit;
}

$verifiedUID = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'cancel-request') {
    // Check if a reason is provided
    $reason = isset($_POST['reason']) ? $_POST['reason'] : "";

    // Check if order_id is provided
    if (!isset($_POST['order_id_input'])) {
        echo json_encode(['success' => false, 'message' => 'Failed to get order details']);
        exit;
    }

    $order_id = $_POST['order_id_input'];

    // Fetch order details
    $query = "SELECT transaction_number FROM orders WHERE order_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if order exists
    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    $txn = $row['transaction_number'];

    $stmt->close();

    // Insert cancellation request into cancellation_request table
    $stmt = $link->prepare("INSERT INTO cancellation_request (order_id, txn, userid, reason, date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isis", $order_id, $txn, $verifiedUID, $reason);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true, 'message' => 'You have successfully requested a cancellation for order with TXN: ' . $txn, 'transaction_number' => $txn, 'userid' => $verifiedUID]);
    $link->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
