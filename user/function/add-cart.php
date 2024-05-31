<?php
session_start();
require_once "../database/connection.php"; // Include your database connection file

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the product ID and quantity are provided
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        // Sanitize the product ID, user ID, and quantity
        $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
        // Log the quantity
        error_log("Quantity fetched: " . $quantity);

        // Check if the user is logged in
        if (!$user_id) {
            // If user is not logged in, handle the case according to your application logic
            // For example, redirect to the login page or prompt the user to log in
            http_response_code(401); // Unauthorized
            echo "You need to log in to add items to your cart.";
            exit;
        }

        // Check if the product already exists in the cart for the user
        $query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        if (mysqli_num_rows($result) > 0) {
            // If the product already exists in the cart, update the quantity
            $row = mysqli_fetch_assoc($result);
            $new_quantity = $row['quantity'] + $quantity;

            // Prepare the SQL statement to update the quantity
            $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $update_stmt = mysqli_prepare($link, $update_query);

            if ($update_stmt) {
                // Bind parameters and execute the statement
                mysqli_stmt_bind_param($update_stmt, "iii", $new_quantity, $user_id, $product_id);
                if (mysqli_stmt_execute($update_stmt)) {
                    // Quantity updated successfully
                    echo "Product quantity updated in the carted.";
                } else {
                    // Error executing the statement
                    http_response_code(500); // Internal Server Error
                    echo "An error occurred while updating the product quantity in the cart.";
                }
                mysqli_stmt_close($update_stmt);
            } else {
                // Error preparing the statement
                http_response_code(500); // Internal Server Error
                echo "An error occurred while preparing the SQL statement to update quantity.";
            }
        } else {
            // If the product does not exist in the cart, insert a new row
            $insert_query = "INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, ?, NOW())";
            $insert_stmt = mysqli_prepare($link, $insert_query);

            if ($insert_stmt) {
                // Bind parameters and execute the statement
                mysqli_stmt_bind_param($insert_stmt, "iii", $user_id, $product_id, $quantity);
                if (mysqli_stmt_execute($insert_stmt)) {
                    // Cart item added successfully
                    echo "Product added to cart successfully. QUANTITY: $new_quantity";
                } else {
                    // Error executing the statement
                    http_response_code(500); // Internal Server Error
                    echo "An error occurred while adding the product to the cart.";
                }
                mysqli_stmt_close($insert_stmt);
            } else {
                // Error preparing the statement
                http_response_code(500); // Internal Server Error
                echo "An error occurred while preparing the SQL statement to insert into cart.";
            }
        }
    } else {
        // If the product ID or quantity is not provided, send an error response
        http_response_code(400); // Bad Request
        echo "Product ID and quantity are required.";
    }
} else {
    // If the request method is not POST, send an error response
    http_response_code(405); // Method Not Allowed
    echo "Method Not Allowed";
}
