<?php
// Include database connection
require_once("../../database/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get inventory_id and new stock value from POST data
    $inventory_id = $_POST['inventory_id'];
    $new_stock = $_POST['stock'];

    // Update the stock value in the database
    $query = "UPDATE product_inventory SET stock = ? WHERE inventory_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("ii", $new_stock, $inventory_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update stock']);
    }

    // Close statement and database connection
    $stmt->close();
    mysqli_close($link);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
