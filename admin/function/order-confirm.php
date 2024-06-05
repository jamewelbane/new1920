<?php
session_start();
require_once '../../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify if user is logged in
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['success' => false, 'message' => 'You need to log in first.']);
        exit;
    }

    $order_id = $_POST['order_id'];

    // Check if the order_id exists in cancellation_request table
    $checkQuery = "SELECT * FROM cancellation_request WHERE order_id = ?";
    $stmtCheck = $link->prepare($checkQuery);
    $stmtCheck->bind_param("i", $order_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Order ID exists in cancellation_request table, update the status to 3
        $updateCancellationQuery = "UPDATE cancellation_request SET status = 3 WHERE order_id = ?";
        $stmtUpdateCancellation = $link->prepare($updateCancellationQuery);
        $stmtUpdateCancellation->bind_param("i", $order_id);
        $stmtUpdateCancellation->execute();
        $stmtUpdateCancellation->close();
    }

    $stmtCheck->close();

    // Update the order status to 'Confirmed'
    $updateOrderQuery = "UPDATE orders SET order_status = 'Confirmed' WHERE order_id = ?";
    $stmtUpdateOrder = $link->prepare($updateOrderQuery);
    $stmtUpdateOrder->bind_param("i", $order_id);

    if ($stmtUpdateOrder->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order confirmed successfully. Please communicate with the buyer about the shipping method']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to confirm the order.']);
    }

    $stmtUpdateOrder->close();
    $link->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
