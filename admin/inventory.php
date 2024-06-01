<html lang="en">
<?php
session_start();
require_once("../database/connection.php");
require_once("function/admin-function.php");
require("function/check-login.php");
include("head.html");


check_login_user_universal($link);
if (!check_login_user_universal($link)) {
    header("Location: index");
    exit;
} else {
    $verifiedUID = $_SESSION['admin_id'];
}


?>


</head>
<body>



    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        <?php include("sidebar.php"); ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <?php include("navbar.php") ?>
            <!-- partial -->
            <div class="main-panel">

                <div class="content-wrapper">



                    <div class="row">

                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Product list</h4>
                                    <p class="card-description">Click the <code>view</code> button to check/update the available stock</p>
                                    <div class="table-responsive">
                                        <table id="productTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product ID</th>
                                                    <th>Name</th>
                                                    <th>Created</th>
                                                    <th>Status
                                                        <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="The stock status depends on the availability of shoe sizes."></i>
                                                    </th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php


                                                // Fetch products
                                                $query = "SELECT product_id, prod_name, createdAt FROM products";
                                                $result = mysqli_query($link, $query);

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $productId = $row['product_id'];
                                                    $createdAt = date('d M Y', strtotime($row['createdAt']));

                                                    // Determine stock status
                                                    $statusQuery = "SELECT stock FROM product_inventory WHERE product_id = '$productId'";
                                                    $statusResult = mysqli_query($link, $statusQuery);
                                                    $stocks = [];
                                                    while ($statusRow = mysqli_fetch_assoc($statusResult)) {
                                                        $stocks[] = $statusRow['stock'];
                                                    }

                                                    if (empty($stocks) || array_sum($stocks) == 0) {
                                                        $status = '<label class="badge badge-danger">Out Of Stock</label>';
                                                    } elseif (min($stocks) < 3) {
                                                        $status = '<label class="badge badge-warning">Low Stock</label>';
                                                    } else {
                                                        $status = '<label class="badge badge-success">In Stock</label>';
                                                    }

                                                    echo "<tr>
                                                        <td>{$row['product_id']}</td>
                                                        <td>{$row['prod_name']}</td>
                                                        <td>{$createdAt}</td>
                                                        <td>{$status}</td>
                                                        <td><button type='button' data-product_id='{$row['product_id']}' class='view-inventory btn btn-outline-secondary btn-icon-text'><i class='fas fa-box-open'></i></button></td>
                                                    </tr>";
                                                }

                                                mysqli_close($link);
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <?php include("footer.html") ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


    <?php include("assets/injectables.html"); ?>
</body>
<script>
    $(function() {
        // Use event delegation for the click event
        $(document).on('click', '.view-inventory', function() {
            var product_id = $(this).data('product_id');
            $.ajax({
                url: 'function/manage-inventory.php',
                type: 'post',
                data: {
                    product_id: product_id
                },
                success: function(response) {
                    $('.inventorymodalbody').html(response);
                    $('#inventoryModal').modal('show');

                    $(document).on('click', '#close-btn', function() {
                        $('#inventoryModal').modal('hide');
                    });
                }
            });
        });
    });
</script>


<!-- Modal for inventory -->
<div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inventory</h5>
                <button type="button" class="close" id="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="inventorymodalbody modal-body">
                
                <!-- Content will be loaded here from edit-temp-question.php -->
            </div>
        </div>
    </div>
</div>
<!-- datatable -->

<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });
</script>

<!-- tooltip for status -->
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>



</html>