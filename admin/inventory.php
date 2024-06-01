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
                                    <p class="card-description"> Click the <code>view</code> button to check/update the available stock
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product ID</th>
                                                    <th>Name</th>
                                                    <th>Created</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>NB300</td>
                                                    <td>12 May 2024</td>
                                                    <td><label class="badge badge-success">In Stock</label></td>
                                                    <td><button type="button" class="view-inventory btn btn-outline-secondary btn-icon-text">View <i class="fas fa-eye"></i></button></td>
                                                </tr>

                                                <tr>
                                                    <td>2</td>
                                                    <td>NB600</td>
                                                    <td>13 May 2024</td>
                                                    <td><label class="badge badge-danger">Out Of Stock</label></td>
                                                    <td><button type="button" class="view-inventory btn btn-outline-secondary btn-icon-text">View <i class="fas fa-eye"></></button></td>
                                                </tr>
                                                
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

</html>