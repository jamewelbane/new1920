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
                                    <h4 class="card-title">Completed Transaction</h4>

                                    <div class="table-responsive">
                                        <table id="productTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Txn</th>
                                                    <th>User</th>
                                                    <th>Paid Amount</th>
                                                    <th>Date Ordered</th>
                                                    <th>Date Completed</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php


                                                // Fetch orders
                                                $query = "SELECT * FROM orders WHERE order_status = 'Completed'";
                                                $result = mysqli_query($link, $query);

                                                while ($row = mysqli_fetch_assoc($result)) {

                                                    $userid = $row['userid'];

                                                    // Fetch username
                                                    $usernameQuery = "SELECT username FROM users WHERE userid = ?";
                                                    $stmtUsername = $link->prepare($usernameQuery);
                                                    $stmtUsername->bind_param("i", $userid);
                                                    $stmtUsername->execute();
                                                    $resultUsername = $stmtUsername->get_result();
                                                    $username = $resultUsername->fetch_assoc()['username'];
                                                    $stmtUsername->close();

                                                    $status = $row['order_status'];

                                                    $order_id = $row['order_id'];
                                                    $transaction_number = $row['transaction_number'];

                                                    $createdAt = date('d M Y', strtotime($row['created_at']));
                                                    $updatedAt = date('d M Y', strtotime($row['updated_at']));

                                                    if ($status === 'Completed') {
                                                        $status = '<label class="badge badge-success">Completed</label>';
                                                    }

                                                    echo "<tr>
                                                        <td>{$row['order_id']}</td>
                                                        <td>{$row['transaction_number']}</td>
                                                        <td>{$username}</td>
                                                        <td style='color: green;'>₱" . number_format($row['total_amount'], 2, '.', ',') . "</td>
                                                        <td>{$createdAt}</td>
                                                        <td>{$updatedAt}</td>
                                                        <td>{$status}</td>
                                                        <td>
                                                            <button type='button' title='Item list' data-txn='{$row['transaction_number']}' class='view-order btn btn-primary btn-md'><i class='fas fa-shopping-cart'></i></button>
                                                            <button type='button' title='User info' data-userid='{$row['userid']}' class='view-user btn btn-secondary btn-md'><i class='fas fa-user'></i></button>
                                                            <button type='button' data-order_id='{$order_id}' class='note-user btn btn-warning btn-md'><i class='mdi mdi-note-text'></i></button>
                                                            <button type='button' title='Proof of Payment' data-order_id='{$row['order_id']}' class='proof-payment btn btn-info btn-md'><i class='fas fa-file-invoice-dollar'></i></button>
                                                            
                                                        </td>
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
        $(document).on('click', '.view-order', function() {
            var txn = $(this).data('txn');
            $.ajax({
                url: 'function/order-list.php',
                type: 'post',
                data: {
                    txn: txn
                },
                success: function(response) {
                    $('.ordermodalbody').html(response);
                    $('#orderModal').modal('show');

                    $(document).on('click', '#close-btn', function() {
                        $('#orderModal').modal('hide');
                    });
                }
            });
        });
    });
</script>


<!-- Modal for inventory -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="ModalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inventory</h5>
                <button type="button" class="close" id="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="ordermodalbody modal-body">

                <!-- Content will be loaded here from edit-temp-question.php -->
            </div>
        </div>
    </div>
</div>


<!-- proof of payment -->
<script>
    $(function() {
        // Use event delegation for the click event
        $(document).on('click', '.proof-payment', function() {
            var order_id = $(this).data('order_id');
            $.ajax({
                url: 'function/proof-payment-view.php',
                type: 'post',
                data: {
                    order_id: order_id
                },
                success: function(response) {
                    $('.proofPaymentModalBody').html(response);
                    $('#proofPaymentModal').modal('show');

                    $(document).on('click', '#close-btn', function() {
                        $('#proofPaymentModal').modal('hide');
                    });
                }
            });
        });
    });
</script>


<!-- Modal for proof of payment -->
<div class="modal fade" id="proofPaymentModal" tabindex="-1" role="dialog" aria-labelledby="ModalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proof of Payment</h5>
                <button type="button" class="close" id="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="proofPaymentModalBody modal-body">

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
<!-- modal for note -->
<script>
    $(function() {
        // Use event delegation for the click event
        $(document).on('click', '.note-user', function() {
            var order_id = $(this).data('order_id');
            $.ajax({
                url: 'function/user-note.php',
                type: 'post',
                data: {
                    order_id: order_id
                },
                success: function(response) {
                    $('.noteModalBody').html(response);
                    $('#noteModal').modal('show');

                    $(document).on('click', '#close-btn', function() {
                        $('#noteModal').modal('hide');
                    });
                }
            });
        });
    });
</script>


<!-- Modal for note -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="ModalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note</h5>
                <button type="button" class="close" id="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="noteModalBody modal-body">

                <!-- Content will be loaded here from user-note.php -->
            </div>
        </div>
    </div>
</div>


</html>