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
<style>
    @media only screen and (max-width: 767px) {
        #approve_cancel {
            margin-top: 10px;
        }
    }

 
</style>

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
                                    <h4 class="card-title">Pending Transaction</h4>

                                    <div class="table-responsive">
                                        <table id="productTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Userid</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch orders
                                                $query = "SELECT * FROM users";
                                                $result = mysqli_query($link, $query);

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $userid = $row['userid'];

                                                    

                                                    $username = $row['username'];
                                                    $email = $row['email'];
                                                   

                                                  


                                                    echo "<tr>
                                                    <td>{$userid}</td>
                                                    <td>{$username}</td>
                                                    <td>{$email}</td>
           
                                                   
                                                        <td>
                                                            <button type='button' data-userid='{$userid}' class='view-order btn btn-primary btn-md'><i class='fas fa-shopping-cart'></i></button>

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
            var userid = $(this).data('userid');
            $.ajax({
                url: 'function/user-order.php',
                type: 'post',
                data: {
                    userid: userid
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


<!-- Modal for cart list -->
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="ModalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cart</h5>
                    <button type="button" class="close" id="close-btn" data-dismiss="modal">&times;</button>
                </div>
                <div class="ordermodalbody modal-body">

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


<!-- view user -->
<script>
    $(function() {
        // Use event delegation for the click event
        $(document).on('click', '.view-user', function() {
            var userid = $(this).data('userid');
            $.ajax({
                url: 'function/view-user.php',
                type: 'post',
                data: {
                    userid: userid
                },
                success: function(response) {
                    $('.viewUserModalBody').html(response);
                    $('#viewUserModal').modal('show');

                    $(document).on('click', '#close-btn', function() {
                        $('#viewUserModal').modal('hide');
                    });
                }
            });
        });
    });
</script>


<!-- Modal for proof of payment -->
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="ModalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User information</h5>
                <button type="button" class="close" id="close-btn" data-dismiss="modal">&times;</button>
            </div>
            <div class="viewUserModalBody modal-body">

                <!-- Content will be loaded here from edit-temp-question.php -->
            </div>
        </div>
    </div>
</div>






</html>