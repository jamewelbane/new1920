<?php

require_once("../../database/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get order_id from POST data
    $order_id = $_POST['order_id'];



    // Fetch imgURL for the specified order_id
    $query = "SELECT imgURL FROM orders WHERE order_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imgURL = $row['imgURL'];
    } else {
        $imgURL = null;
    }

    $stmt->close();

    $conf_message = "This transaction has requested a cancellation for this order. If you proceed, you will reject their request for cancellation. Proceed anyway?";

    // check if the order is pending for cancellation
    $queryCheckCancellation = "SELECT status FROM cancellation_request WHERE order_id = ?";
    $stmtqueryCheckCancellation = $link->prepare($queryCheckCancellation);


    $stmtqueryCheckCancellation->bind_param("i", $order_id);

    $stmtqueryCheckCancellation->execute();

    // Fetch the result
    $stmtqueryCheckCancellation->bind_result($cancel_order);
    $stmtqueryCheckCancellation->fetch();
    if (!isset($cancel_order)) {
        $cancel_order = '';
        $conf_message = "Confirm the order? Make sure you double check the payment";
    }



    $stmtqueryCheckCancellation->close();
    $link->close();
} else {
    echo "Invalid request.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Image</title>
</head>

<body>
    <?php if ($imgURL) : ?>
        <img src="<?php echo htmlspecialchars($imgURL); ?>" alt="Proof of Payment" style="max-width: 100%; height: auto;">
        <div style="text-align: center; margin-top: 10px;">
            <button data-order_id="<?php echo htmlspecialchars($order_id); ?>" id="confirm_order" class="btn btn-primary btn-fw">Confirm</button>
            <?php
            if ($cancel_order === 'Pending') {
            ?>
                <button data-order_id="<?php echo htmlspecialchars($order_id); ?>"id="approve_cancel" class="btn btn-danger btn-fw">Approve Cancellation</button>
            <?php
            }
            ?>
        </div>
    <?php else : ?>
        <p>No proof of payment found for this order.</p>
    <?php endif; ?>
</body>

<script>
    document.getElementById('confirm_order').addEventListener('click', function() {
        if (confirm('<?php echo $conf_message ?>')) {
            var order_id = this.getAttribute('data-order_id');
            // Create form data
            var formData = new FormData();
            formData.append('order_id', order_id);

            // Proceed with order confirmation
            fetch('function/order-confirm.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        window.location.href = 'orders-pending';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>



<script>
    document.getElementById('approve_cancel').addEventListener('click', function() {
        if (confirm('Approve cancellation for this order? Make sure you have sent the money back to the user before doing this action')) {
            var order_id = this.getAttribute('data-order_id');
            // Create form data
            var formData = new FormData();
            formData.append('order_id', order_id);

            // Proceed with order confirmation
            fetch('function/cancellation-approval.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        window.location.href = 'orders-pending';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>




</html>