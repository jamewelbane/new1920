<?php
session_start();
require_once '../../database/connection.php';
include 'getUserInfo.php';
include 'mailer/main-mailer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify if user is logged in
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['success' => false, 'message' => 'You need to log in first.']);
        exit;
    }

    if (!isset($_POST['order_id'])) {
        echo json_encode(['success' => false, 'message' => 'Failed to get order_id']);
        exit;
    }

    $subject = "Order Confirmation";
    
    $order_id = $_POST['order_id'];
    $userDetails = getUserDetailsByOrderId($link, $order_id);

    if ($userDetails) {
        $user_id = $userDetails['userid'];
        $updated_at = $userDetails['updated_at'];
        $txn = $userDetails['transaction_number'];

        // Fetch user info by user id
        $userInfo = getUserInfo($link, $user_id);
        $name = $userInfo['name'];
        $email = $userInfo['email'];
        $address = $userInfo['address'];

    } else {
        echo json_encode(['success' => false, 'message' => 'No details found for the given order ID.']);
        exit;
    }
    $body = "This email is to confirm your order with the transaction number: $txn. Your payment has been successfully verified. Please keep your contact lines open as our representative will get in touch with you via email or phone regarding the shipment process.";
    // Fetch order list
    $query = "SELECT * FROM order_list WHERE transaction_number = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $txn);
    $stmt->execute();
    $result = $stmt->get_result();

    // Start constructing the table rows
    $totalPrice = 0;
    $orderRows = '';
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];

        // Fetch product name
        $prod_nameQuery = "SELECT prod_name FROM products WHERE Product_id = ?";
        $stmtProd_name = $link->prepare($prod_nameQuery);
        $stmtProd_name->bind_param("i", $product_id);
        $stmtProd_name->execute();
        $resultProd_name = $stmtProd_name->get_result();
        $prod_name_row = $resultProd_name->fetch_assoc();
        $prod_name = $prod_name_row['prod_name'];
        $stmtProd_name->close();
        $totalPrice += $row['total_price'];
        // Add row to the table
        $orderRows .= '<tr>';
        $orderRows .= '<td>' . htmlspecialchars($prod_name) . '</td>';
        $orderRows .= '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        $orderRows .= '<td>₱' . number_format($row['total_price'], 2, '.', ',') . '</td>';
        $orderRows .= '</tr>';
    }
    $totalPriceFormatted = '₱' . number_format($totalPrice, 2, '.', ',');

    // Construct the complete message with the table
    // $message = '
    // <table class="table">
    //   <thead>
    //     <tr>
    //       <th>Product</th>
    //       <th>Quantity</th>
    //       <th>Price</th>
    //     </tr>
    //   </thead>
    //   <tbody>
    //     ' . $orderRows . '
    //   </tbody>
    // </table>';


    $message = '
    <h1>Order Confirmation</h1>
    <p>Dear ' . $name . ',</p>
    <p>' . $body . '</p>
    <h2>Order Details</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
        ' . $orderRows . '
      </tbody>
    </table>
    <p><strong>Total: ' . $totalPriceFormatted .'</strong></p>
    <p>You can track your order by visiting our website or contacting us at +63 9102579678 or support@1920snkrs.shop.</p>
    <a href="1920snkrs.shop/user/transactions" class="btn">Track Your Order</a>
    <p>Thank you for your business!</p>
    <p>Sincerely,</p>
    <p>1920 SNKRS</p>
    ';

    // Check if the order_id exists in cancellation_request table
    $checkQuery = "SELECT * FROM cancellation_request WHERE order_id = ?";
    $stmtCheck = $link->prepare($checkQuery);
    $stmtCheck->bind_param("i", $order_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // Order ID exists in cancellation_request table, update the status to 'Rejected'
        $updateCancellationQuery = "UPDATE cancellation_request SET status = 'Rejected' WHERE order_id = ?";
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
    

        send_email_user($email, $name, $message, $subject, $body);
        echo json_encode(['success' => true, 'message' => 'Order confirmed successfully. Please communicate with the buyer about the shipping method']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to confirm the order.']);
    }

    $stmtUpdateOrder->close();
    $link->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
