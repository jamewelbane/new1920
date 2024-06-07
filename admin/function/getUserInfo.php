<?php 

// Function to get user id by order id
function getUserDetailsByOrderId($link, $order_id)
{
    $query = "SELECT userid, updated_at, transaction_number FROM orders WHERE order_id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    return $row;
}

// Function to get user info by user_id
function getUserInfo($link, $user_id)
{
    $query = "SELECT name, email, address FROM user_info WHERE user_id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userInfo = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    return $userInfo;
}

?>