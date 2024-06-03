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

    
    $query = "UPDATE orders SET order_status = 'Confirmed' WHERE order_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order confirmed successfully. Please communicate with buyer about the shipping method']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to confirm the order.']);
    }

    $stmt->close();
    $link->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
