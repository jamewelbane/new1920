<?php

require_once("../../database/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product_id from POST data
    $txn = $_POST['txn'];

    // Fetch inventory data for the specified product_id
    $query = "SELECT * FROM order_list WHERE transaction_number = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("s", $txn);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store fetched data in an array
    $orderListData = [];
    while ($row = $result->fetch_assoc()) {
        $orderListData[] = $row;
    }


    // Display inventory data with DataTables
    echo '<table id="orderListTable" class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ProductID</th>';
    echo '<th>Name</th>';
    echo '<th>Size</th>';
    echo '<th>Quantity</th>';
    echo '<th>Price</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($orderListData as $row) {

        $product_id = $row['product_id'];
        // Fetch product name
        $prod_nameQuery = "SELECT prod_name FROM products WHERE Product_id = ?";
        $stmtProd_name = $link->prepare($prod_nameQuery);
        $stmtProd_name->bind_param("i", $product_id);
        $stmtProd_name->execute();
        $resultProd_name = $stmtProd_name->get_result();
        $prod_name_row = $resultProd_name->fetch_assoc(); // Fetching the row
        $prod_name = $prod_name_row['prod_name']; // Extracting the product name
        $stmtProd_name->close();

        echo '<tr>';
        echo '<td>' . $row['product_id'] . '</td>';
        echo '<td>' . $prod_name . '</td>'; // Corrected embedding of $prod_name
        echo '<td>' . $row['prod_size'] . '</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>₱' . number_format($row['total_price'], 2, '.', ',') . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

?>
    <script>
        $(document).ready(function() {
            $('#orderListTable').DataTable({
                "lengthChange": false,
                "searching": false
            });


        });
    </script>
<?php
} else {
    echo "Invalid request.";
}
?>