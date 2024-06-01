<?php
session_start();
require '../../database/connection.php';

if (!isset($_SESSION['userid'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$userid = $_SESSION['userid'];

if (isset($_POST['cart_id'], $_POST['product_id'], $_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID or quantity']);
        exit;
    }

    // Fetch the available stock for the product
    $query = "SELECT stock FROM product_inventory WHERE product_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit;
    }

    $row = $result->fetch_assoc();
    $availableStock = $row['stock'];

    if ($quantity > $availableStock) {
        echo json_encode(['status' => 'error', 'message' => 'Requested quantity exceeds available stock']);
        exit;
    }

    // Update the quantity in the cart
    $query = "UPDATE cart SET quantity = ? WHERE userid = ? AND cart_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("iii", $quantity, $userid, $cart_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Quantity updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update quantity']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product ID, cart ID, and quantity are required']);
}
?>
