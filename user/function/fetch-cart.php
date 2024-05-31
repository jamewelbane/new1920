<?php
session_start();
require '../../database/connection.php';

$userid = $_SESSION['userid']; // Assuming user ID is stored in session

if (!isset($userid)) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Fetch cart items and product details including discount information
$query = "
    SELECT 
        c.cart_id,
        c.product_id, 
        c.prod_size, 
        c.quantity, 
        p.prod_name, 
        p.price AS original_price, 
        p.ImageURL, 
        pd.onDiscount, 
        pd.Discount
    FROM cart c
    JOIN products p ON c.product_id = p.product_id
    LEFT JOIN product_data pd ON p.product_id = pd.product_id
    WHERE c.userid = ?
";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$subtotal = 0.00;

while ($row = $result->fetch_assoc()) {
    $originalPrice = floatval($row['original_price']);
    $discountedPrice = $originalPrice;

    if ($row['onDiscount'] == 1) {
        $discountPercentage = floatval($row['Discount']);
        $discountedPrice = $originalPrice - ($originalPrice * ($discountPercentage / 100));
    }

    $rowTotal = $discountedPrice * intval($row['quantity']);
    $subtotal += $rowTotal;

    $row['original_price'] = number_format($originalPrice, 2);
    $row['discounted_price'] = number_format($discountedPrice, 2);
    $row['row_total'] = number_format($rowTotal, 2);

    $cartItems[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $cartItems, 'subtotal' => number_format($subtotal, 2)]);
?>