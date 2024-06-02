<style>
    .status-guide {
        margin-bottom: 10px;
    }
</style>
<div class="status-guide"><span class="badge badge-success">In Stock:</span> All sizes have a stock value of 3 or more</div>
<div class="status-guide"><span class="badge badge-warning">Low Stock:</span> One or more sizes have a stock value less than 3</div>
<div class="status-guide"><span class="badge badge-danger">Out of Stock:</span> No available stock for all sizes</div>


<?php

require_once("../../database/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product_id from POST data
    $product_id = $_POST['product_id'];

    // Fetch inventory data for the specified product_id
    $query = "SELECT inventory_id, prod_size, stock FROM product_inventory WHERE product_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store fetched data in an array
    $inventoryData = [];
    while ($row = $result->fetch_assoc()) {
        $inventoryData[] = $row;
    }

    // Close database connection
    mysqli_close($link);

    // Display inventory data with DataTables
    echo '<table id="inventoryTable" class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Inventory ID</th>';
    echo '<th>Product Size</th>';
    echo '<th>Stock</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($inventoryData as $row) {
        echo '<tr>';
        echo '<td>' . $row['inventory_id'] . '</td>';
        echo '<td>' . $row['prod_size'] . '</td>';
        echo '<td class="editable" contenteditable="true" onBlur="updateStock(' . $row['inventory_id'] . ', this.innerText)" onkeypress="return isNumber(event)">' . $row['stock'] . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
?>
    <script>
        $(document).ready(function() {
            $('#inventoryTable').DataTable({
                "lengthChange": false, 
                "searching": false 
            });

            // Add CSS class to editable cells for styling
            $('.editable').addClass('editable-cell');
        });


        function isNumber(event) {
            var charCode = event.which ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        function updateStock(inventoryId, newStock) {
            // Send AJAX request to update stock
            $.ajax({
                url: 'function/update-stock.php',
                type: 'post',
                data: {
                    inventory_id: inventoryId,
                    stock: newStock
                },
                success: function(response) {
                    console.log(response); // Handle success response
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Handle error response
                }
            });
        }
    </script>
<?php
} else {
    echo "Invalid request.";
}
?>


<style>
    .editable-cell {

        transition: background-color 0.3s;
        /* Smooth transition */
    }

    .editable-cell:hover,
    .editable-cell:focus {
        background-color: #191c24;
        /* Change background color on hover or focus */
        color: white;
    }
</style>


