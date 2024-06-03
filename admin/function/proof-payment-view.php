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
        
    <?php else : ?>
        <p>No proof of payment found for this order.</p>
    <?php endif; ?>
</body>

</html>