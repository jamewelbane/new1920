<?php
session_start();
require '../../database/connection.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    exit;
}

// Get user ID from session
$userid = $_SESSION['userid'];
$currentTimestamp = date("Y-m-d H:i:s");

// Check if product ID and quantity are set
if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];

    // Validate inputs
    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product ID or quantity']);
        exit;
    }

    // Validate inputs
    if (empty($size)) {
        echo json_encode(['status' => 'error', 'message' => 'Select a size']);
        exit;
    }


    // Check if product exists and has sufficient stock
    $query = "SELECT stock FROM product_inventory WHERE product_id = ? AND prod_size = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("is", $product_id, $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'This product is out of stock']);
        exit;
    }

    $row = $result->fetch_assoc();
    $stock = $row['stock'];

    // Check if the product is already in the cart
    $query = "SELECT quantity FROM cart WHERE userid = ? AND product_id = ? AND prod_size = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("iis", $userid, $product_id, $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product already exists in the cart, update the quantity
        $row = $result->fetch_assoc();
        $current_quantity = $row['quantity'];

        // Calculate the total quantity after the update
        $total_quantity = $current_quantity + $quantity;

        // Check if the total quantity exceeds the available stock
        if ($total_quantity > $stock) {
            echo json_encode(['status' => 'error', 'message' => 'Exceeds available stock']);
            exit;
        }

        // Update the quantity in the cart
        $query = "UPDATE cart SET quantity = ?, updated_on = ? WHERE userid = ? AND product_id = ? AND prod_size = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("isiis", $total_quantity, $currentTimestamp, $userid, $product_id, $size);
    } else {
        // Product does not exist in the cart, insert a new record
        // Check if initial quantity exceeds available stock
        if ($quantity > $stock) {
            echo json_encode(['status' => 'error', 'message' => 'Exceeds available stock']);
            exit;
        }

        // Insert new record into the cart
        $query = "INSERT INTO cart (userid, product_id, prod_size, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("iisi", $userid, $product_id, $size, $quantity);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product to cart']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product ID and quantity are required']);
}
?>

