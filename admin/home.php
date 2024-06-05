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


$checkQuery = "SELECT 
                    (SELECT COUNT(*) FROM orders WHERE order_status = 'Pending') AS pending_orders, 
                    (SELECT COUNT(*) FROM orders WHERE order_status = 'Confirmed') AS pending_shipment,
                    DATE_FORMAT((SELECT MAX(updated_at) FROM orders WHERE order_status = 'Pending'), '%m/%d/%Y, %h:%i %p') AS latest_pending_timestamp,
                    DATE_FORMAT((SELECT MAX(updated_at) FROM orders WHERE order_status = 'Confirmed'), '%m/%d/%Y, %h:%i %p') AS latest_confirmed_timestamp";
$stmtCounts = mysqli_prepare($link, $checkQuery);
mysqli_stmt_execute($stmtCounts);
$resultCounts = mysqli_stmt_get_result($stmtCounts);
$rowCounts = mysqli_fetch_assoc($resultCounts);

$pendingOrdersCount = $rowCounts['pending_orders'];
$pendingShipmentCount = $rowCounts['pending_shipment'];

$latestPendingTimestamp = $rowCounts['latest_pending_timestamp'];
$latestConfirmedTimestamp = $rowCounts['latest_confirmed_timestamp'];

// Free the result set
mysqli_free_result($resultCounts);



$checkQueryStock = "SELECT 
                    (SELECT COUNT(*) FROM product_inventory WHERE stock < 4) AS low_stock_item, 
                    (SELECT COUNT(*) FROM product_inventory WHERE stock < 3) AS in_stock_item,
                    (SELECT COUNT(*) FROM product_inventory WHERE stock = 0) AS no_stock_item";

$stmtCountsStock = mysqli_prepare($link, $checkQueryStock);
mysqli_stmt_execute($stmtCountsStock);
$resultCountsStock = mysqli_stmt_get_result($stmtCountsStock);
$rowCountsStock = mysqli_fetch_assoc($resultCountsStock);

$total_low_stock = $rowCountsStock['low_stock_item'];
$total_in_stock = $rowCountsStock['in_stock_item'];
$total_no_stock = $rowCountsStock['no_stock_item'];



// Free the result set
mysqli_free_result($resultCountsStock);


// get the total pair sold

$completedTransactionQuery = "SELECT transaction_number FROM orders WHERE order_status = 'Completed'";
$stmtCompletedTransaction = mysqli_prepare($link, $completedTransactionQuery);
mysqli_stmt_execute($stmtCompletedTransaction);
$resultCompletedTransaction = mysqli_stmt_get_result($stmtCompletedTransaction);


$totalPairsSold = 0;
while ($rowCompletedTransaction = mysqli_fetch_assoc($resultCompletedTransaction)) {
    $transactionNumber = $rowCompletedTransaction['transaction_number'];


    $totalQuantityQuery = "SELECT SUM(quantity) AS total_quantity FROM order_list WHERE transaction_number = ?";
    $stmtTotalQuantity = mysqli_prepare($link, $totalQuantityQuery);
    mysqli_stmt_bind_param($stmtTotalQuantity, "s", $transactionNumber);
    mysqli_stmt_execute($stmtTotalQuantity);
    $resultTotalQuantity = mysqli_stmt_get_result($stmtTotalQuantity);
    $rowTotalQuantity = mysqli_fetch_assoc($resultTotalQuantity);
    

    $totalPairsSold += $rowTotalQuantity['total_quantity'];
    

    mysqli_free_result($resultTotalQuantity);
}

mysqli_free_result($resultCompletedTransaction);

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
            <div class="col-12 grid-margin stretch-card">
              <div class="card corona-gradient-card">
                <div class="card-body py-0 px-0 px-sm-3">
                  <div class="row align-items-center">
                    <div class="col-4 col-sm-3 col-xl-2">
                      <img src="assets/images/dashboard/Group126@2x.png" class="gradient-corona-img img-fluid" alt="">
                    </div>
                    <div class="col-5 col-sm-7 col-xl-8 p-0">
                      <h4 class="mb-1 mb-sm-0">Want even more features?</h4>
                      <p class="mb-0 font-weight-normal d-none d-sm-block">Get in touch with us for inquiries or assistance.</p>
                    </div>
                    <div class="col-3 col-sm-2 col-xl-2 pl-0 text-center">
                      <span>
                        <a href="https://www.bootstrapdash.com/product/corona-admin-template/" target="_blank" class="btn btn-outline-light btn-rounded get-started-btn">Contact DEV</a>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-9">
                      <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0"><?= $total_in_stock ?></h3>
                        <p class="text-success ml-2 mb-0 font-weight-medium">pair</p>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="icon icon-box-success ">
                        <span class="mdi mdi-check-circle-outline"></span>
                      </div>
                    </div>
                  </div>
                  <h6 class="text-muted font-weight-normal">In-stock</h6>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-9">
                      <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0"><?= $total_low_stock ?></h3>
                        <p class="text-warning ml-2 mb-0 font-weight-medium">pair</p>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="icon icon-box-warning">
                        <span class="mdi mdi-alert-circle-outline"></span>
                      </div>
                    </div>
                  </div>
                  <h6 class="text-muted font-weight-normal">Low-stock</h6>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-9">
                      <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0"><?= $total_no_stock ?></h3>
                        <p class="text-danger ml-2 mb-0 font-weight-medium">pair</p>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="icon icon-box-danger">
                        <span class="mdi mdi-close-circle-outline"></span>
                      </div>
                    </div>
                  </div>
                  <h6 class="text-muted font-weight-normal">Out-of-stock</h6>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-9">
                      <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0"><?= $totalPairsSold ?></h3>
                        <p class="text-success ml-2 mb-0 font-weight-medium">pair</p>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="icon icon-box-success ">
                        <span class="mdi mdi-cart-outline"></span>
                      </div>
                    </div>
                  </div>
                  <h6 class="text-muted font-weight-normal">Sold</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Pending Transactions</h4>
                  <canvas id="transaction-history" class="transaction-chart"></canvas>
                  <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                    <div class="text-md-center text-xl-left">
                      <h6 class="mb-1">Pending Orders</h6>
                      <p class="text-muted mb-0"><?= $latestPendingTimestamp ?></p>
                    </div>
                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                      <h6 class="font-weight-bold mb-0"><?= $pendingOrdersCount ?></h6>
                    </div>
                  </div>
                  <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                    <div class="text-md-center text-xl-left">
                      <h6 class="mb-1">Pending Shipment</h6>
                      <p class="text-muted mb-0"><?= $latestConfirmedTimestamp ?></p>
                    </div>
                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                      <h6 class="font-weight-bold mb-0"><?= $pendingShipmentCount ?></h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-row justify-content-between">
                    <h4 class="card-title mb-1">Sale Performance Summary</h4>

                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="preview-list">
                        <div class="preview-item border-bottom">
                          <div class="preview-thumbnail">
                            <div class="preview-icon bg-primary">
                              <i class="mdi mdi-book-open-page-variant"></i>
                            </div>
                          </div>
                          <div class="preview-item-content d-sm-flex flex-grow">
                            <div class="flex-grow">
                              <h6 class="preview-subject">Performace Chart</h6>
                              <!-- message -->
                              <p class="text-muted mb-0">As of 05/14/2024, 03:00 PM</p>
                              <!-- end -->
                            </div>
                            <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                              <p class="text-muted mb-0">Category </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
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


  <?php include("assets/injectables.html");
  include("function/dashboard/chart.php"); ?>

</body>

</html>