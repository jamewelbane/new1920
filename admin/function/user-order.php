<?php

require_once("../../database/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get userid from POST data
    $userid = $_POST['userid'];

    // Fetch order data for the specified userid
    $query = "SELECT * FROM orders WHERE userid = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $userid);  // Change 's' to 'i' if userid is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Store fetched data in an array
    $orderListData = [];
    while ($row = $result->fetch_assoc()) {
        $orderListData[] = $row;
    }

    // Display order data with DataTables
    echo '<div class="table-responsive">';
    echo '<table id="orderListTable" class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Transaction #</th>';
    echo '<th>Total</th>';
    echo '<th>Ordered On</th>';
    echo '<th>Status</th>';
    echo '<th>Updated</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($orderListData as $row) {
        // Format the date
        $createdAt = new DateTime($row['created_at']);
        $createdAtFormatted = $createdAt->format('m/d/Y h:i A');

        $updatedAt = new DateTime($row['updated_at']);
        $updatedAtFormatted = $updatedAt->format('m/d/Y h:i A');

        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['transaction_number']) . '</td>';
        echo '<td>₱' . number_format($row['total_amount'], 2, '.', ',') . '</td>';
        echo '<td>' . htmlspecialchars($createdAtFormatted) . '</td>';
        echo '<td>' . htmlspecialchars($row['order_status']) . '</td>';
        echo '<td>' . htmlspecialchars($updatedAtFormatted) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

} else {
    echo "Invalid request.";
}
?>

<script>
    $(document).ready(function() {
        $('#orderListTable').DataTable({
            "lengthChange": true,
            "searching": false
        });
    });
</script>
