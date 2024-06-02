<?php
session_start();
require_once '../../database/connection.php';

// Verify if user is logged in
if (!isset($_SESSION['userid'])) {
    echo json_encode(["success" => false, "message" => "You need to log in first."]);
    exit;
}

$verifiedUID = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'checkout') {
    // Fetch cart details
    $query = "SELECT product_id, prod_size, quantity FROM cart WHERE userid = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $verifiedUID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Calculate total price (using the updated getProductPrice function)
    $total_price = 0;
    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $prod_size = $row['prod_size'];
        $quantity = $row['quantity'];
        $price = getProductPrice($product_id); // Use the updated function to get the product price
        $row_total = $price * $quantity;
        $total_price += $row_total;

        $cart_items[] = [
            'product_id' => $product_id,
            'prod_size' => $prod_size,
            'quantity' => $quantity,
            'row_total' => $row_total
        ];
    }
    $stmt->close();

    // Generate a unique transaction number
    $transaction_number = uniqid('txn_');

    // Generate a unique token
    $token = bin2hex(random_bytes(16));

    // Insert into orders table
    $stmt = $link->prepare("INSERT INTO orders (transaction_number, userid, total_amount, order_status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("sid", $transaction_number, $verifiedUID, $total_price);
    $stmt->execute();
    $stmt->close();

    // Insert into order_list table
    foreach ($cart_items as $item) {
        $stmt = $link->prepare("INSERT INTO order_list (userid, product_id, prod_size, quantity, total_price, transaction_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisds", $verifiedUID, $item['product_id'], $item['prod_size'], $item['quantity'], $item['row_total'], $transaction_number);
        $stmt->execute();
        $stmt->close();
    }

    // Clear the cart after checkout
    $stmt = $link->prepare("DELETE FROM cart WHERE userid = ?");
    $stmt->bind_param("i", $verifiedUID);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        "success" => true,
        "message" => "Checkout successful. Your transaction number is: $transaction_number",
        "transaction_number" => $transaction_number,
        "userid" => $verifiedUID,
        "token" => $token
    ]);
    $link->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

function getProductPrice($product_id) {
    global $link;
    // Fetch the price from the products table
    $query = "SELECT price FROM products WHERE product_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();

    // Check if the product is on discount and get the discount rate
    $query = "SELECT onDiscount, Discount FROM product_data WHERE product_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($onDiscount, $discount);
    $stmt->fetch();
    $stmt->close();

    // Apply discount if the product is on sale
    if ($onDiscount == 1) {
        $price = $price - ($price * ($discount / 100));
    }

    return $price;
}
?>
