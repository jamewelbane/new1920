<?php

require '../../database/connection.php';


$fetched_cart_id = $_POST['cart_id'];
$cart_id = intval($fetched_cart_id);

// Check if customer exists
$checkQueryCart = "SELECT * FROM cart WHERE cart_id = ?";
if ($stmtCheckCart = mysqli_prepare($link, $checkQueryCart)) {
    mysqli_stmt_bind_param($stmtCheckCart, "i", $cart_id);
    mysqli_stmt_execute($stmtCheckCart);
    $resultCart = mysqli_stmt_get_result($stmtCheckCart);

    if ($resultCart && mysqli_num_rows($resultCart) > 0) {
       
        mysqli_autocommit($link, false); // Turn off autocommit

      
        $queryDeleteCart = "DELETE FROM cart WHERE cart_id = ?";
        if ($stmtDeleteCart = mysqli_prepare($link, $queryDeleteCart)) {
            mysqli_stmt_bind_param($stmtDeleteCart, "i", $cart_id);
            $deleteSuccessCart = mysqli_stmt_execute($stmtDeleteCart);

            $allSuccess = $deleteSuccessCart;

            if ($allSuccess) {
                mysqli_commit($link); 
                echo "Item has been deleted";
            } else {
                mysqli_rollback($link); // if failed, rollback the transaction
                echo "Cannot complete deletion";
            }

           
            mysqli_stmt_close($stmtDeleteCart);
        } else {
            echo "Error preparing delete statement";
        }
    } else {
        echo "Can't find cart data";
    }

    // Close prep statements
    mysqli_stmt_close($stmtCheckCart);
} else {
    echo "Error preparing check statement";
}

$link->close();

?>
