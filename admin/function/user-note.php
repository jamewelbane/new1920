<?php

require_once("../../database/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get order_id from POST data
    $order_id = $_POST['order_id'];

    $note = "";

    // Fetch imgURL for the specified order_id
    $query = "SELECT note FROM orders WHERE order_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $note = $row['note'];
    } else {
        $note = null;
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

</head>

<body>

    <?php
    if ($note === "") {
       echo "<p>No note</p>";
    } else {
        echo "<p>$note</p>";
    }

    ?>

    
</body>






</html>