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

$curr_month = date('F');
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


// Function to get total pairs sold
function getTotalPairsSold($link, $dateCondition = '')
{
  $query = "SELECT transaction_number FROM orders WHERE order_status = 'Completed' $dateCondition";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $totalPairs = 0;
  while ($row = mysqli_fetch_assoc($result)) {
    $transactionNumber = $row['transaction_number'];
    $quantityQuery = "SELECT SUM(quantity) AS total_quantity FROM order_list WHERE transaction_number = ?";
    $stmtQuantity = mysqli_prepare($link, $quantityQuery);
    mysqli_stmt_bind_param($stmtQuantity, "s", $transactionNumber);
    mysqli_stmt_execute($stmtQuantity);
    $resultQuantity = mysqli_stmt_get_result($stmtQuantity);
    $rowQuantity = mysqli_fetch_assoc($resultQuantity);
    $totalPairs += $rowQuantity['total_quantity'];

    mysqli_free_result($resultQuantity);
    mysqli_stmt_close($stmtQuantity);
  }

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  return $totalPairs;
}

// Get the total pairs sold overall
$totalPairsSold = getTotalPairsSold($link);

// Get the total pairs sold today
$currentDate = date('Y-m-d');
$totalPairsSoldToday = getTotalPairsSold($link, "AND DATE(updated_at) = '$currentDate'");

// Get the total pairs sold on the previous day
$previousDate = date('Y-m-d', strtotime('-1 day'));
$totalPairsSoldPreviousDay = getTotalPairsSold($link, "AND DATE(updated_at) = '$previousDate'");

// Calculate the percentage change
if ($totalPairsSoldPreviousDay > 0) {
  $percentageChange = (($totalPairsSoldToday - $totalPairsSoldPreviousDay) / $totalPairsSoldPreviousDay) * 100;
} else {
  $percentageChange = $totalPairsSoldToday > 0 ? 100 : 0; // Handle case when previous day sold is zero
}

// Determine the icon and percentage display
if ($percentageChange > 0) {
  $percentageClass = 'text-success';
  $iconClass2 = 'icon-box-success';
  $iconItem = 'mdi-arrow-top-right';
  $PlusMinus = '+';
} else if ($percentageChange < 0) {
  $percentageClass = 'text-danger';
  $iconClass2 = 'icon-box-danger';
  $iconItem = 'mdi-arrow-bottom-left';
  $percentageChange = abs($percentageChange); // Convert to positive for display
  $PlusMinus = '-';
} else {
  $percentageClass = 'text-muted';
  $iconClass2 = 'icon-box-muted';
  $iconItem = 'mdi-arrow-right';
}

$percentageDisplay = number_format($percentageChange, 2) . '%';





// get the monthly revenue 
$currentMonth = date('m');
$currentYear = date('Y');

$previousMonth = date('m', strtotime('-1 month'));
$previousYear = date('Y', strtotime('-1 month'));

// Function to get total revenue for a given month and year
function getTotalRevenueForMonth($link, $month, $year)
{
  $query = "
        SELECT SUM(total_amount) as total_revenue 
        FROM orders 
        WHERE order_status = 'Completed' AND MONTH(updated_at) = ? AND YEAR(updated_at) = ?
    ";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_bind_param($stmt, 'ii', $month, $year);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $row = mysqli_fetch_assoc($result);
  $totalRevenue = $row['total_revenue'];

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  return $totalRevenue;
}

// Get the total revenue for the current month and previous month
$currentMonthRevenue = getTotalRevenueForMonth($link, $currentMonth, $currentYear);
$previousMonthRevenue = getTotalRevenueForMonth($link, $previousMonth, $previousYear);

// Format the revenues
$formattedCurrentMonthRevenue = number_format($currentMonthRevenue, 2, '.', ',');
$formattedPreviousMonthRevenue = number_format($previousMonthRevenue, 2, '.', ',');

// Calculate the percentage change for monthly revenue
if ($previousMonthRevenue > 0) {
  $percentageChange = (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100;
} else {
  $percentageChange = 0;
}

// Determine the icon and text color based on the percentage change for monthly revenue
if ($percentageChange > 0) {
  $iconClass = 'icon-box-success';
  $iconArrow = 'mdi-arrow-top-right';
  $textColor = 'text-success';
  $PlusMinus = '+';
} else {
  $iconClass = 'icon-box-danger';
  $iconArrow = 'mdi-arrow-bottom-left';
  $textColor = 'text-danger';
  $PlusMinus = '-';
}

// Get the total revenue for the previous day
$previousDate = date('Y-m-d', strtotime('-1 day'));
$previousDayRevenue = getTotalRevenueForDay($link, $previousDate);

// Function to get total revenue for a given date
function getTotalRevenueForDay($link, $date)
{
  // Query to get total revenue for the given date
  $query = "SELECT SUM(total_amount) AS total_revenue FROM orders WHERE DATE(updated_at) = ? AND order_status = 'Completed'";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_bind_param($stmt, "s", $date);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $totalRevenue = $row['total_revenue'] ?? 0; // If no revenue found, default to 0
  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  return $totalRevenue;
}

// Get the total revenue for the current day
$currentDayRevenue = getTotalRevenueForDay($link, date('Y-m-d'));

// Calculate the percentage change for daily revenue
if ($previousDayRevenue > 0) {
  $percentageChangeToday = (($currentDayRevenue - $previousDayRevenue) / $previousDayRevenue) * 100;
} else {
  $percentageChangeToday = 0;
}

// Determine the icon and text color based on the percentage change for daily revenue
if ($percentageChangeToday > 0) {
  $iconClassToday = 'icon-box-success';
  $iconArrowToday = 'mdi-arrow-top-right';
  $textColorToday = 'text-success';
  $PlusMinusToday = '+';
} else {
  $iconClassToday = 'icon-box-danger';
  $iconArrowToday = 'mdi-arrow-bottom-left';
  $textColorToday = 'text-danger';
  $PlusMinusToday = '-';
}

// Format the current day revenue
$formattedCurrentDayRevenue = number_format($currentDayRevenue, 2, '.', ',');



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
                        <a href="#" target="_blank" class="btn btn-outline-light btn-rounded get-started-btn">Contact DEV</a>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-9">
                      <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0">₱<?= $formattedCurrentMonthRevenue ?></h3>
                        <p class="<?= $textColor ?> ml-2 mb-0 font-weight-medium"><?= $PlusMinus ?><?= number_format(abs($percentageChange), 2) ?>%</p>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="icon <?= $iconClass ?>">
                        <span class="mdi <?= $iconArrow ?> icon-item"></span>
                      </div>
                    </div>
                  </div>
                  <h6 class="text-muted font-weight-normal"><?= $curr_month ?> Revenue</h6>
                </div>
              </div>
            </div>

            <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-9">
                      <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0">₱<?= $formattedCurrentDayRevenue ?></h3>
                        <p class="<?= $textColorToday ?> ml-2 mb-0 font-weight-medium"><?= $PlusMinusToday ?><?= number_format(abs($percentageChangeToday), 2) ?>%</p>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="icon <?= $iconClassToday ?>">
                        <span class="mdi <?= $iconArrowToday ?> icon-item"></span>
                      </div>
                    </div>
                  </div>
                  <h6 class="text-muted font-weight-normal">Today's earning</h6>
                </div>
              </div>
            </div>

            <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-9">
                      <div class="d-flex align-items-center align-self-start">
                        <h3 class="mb-0"><?= $totalPairsSoldToday ?></h3>
                        <p class="<?= $percentageClass ?> ml-2 mb-0 font-weight-medium"><?= $percentageChangeToday >= 0 ? $PlusMinus : '-' ?><?= $percentageDisplay ?></p>
  
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="icon <?= $iconClass2 ?>">
                        <span class="mdi <?= $iconItem ?> icon-item"></span>
                      </div>
                    </div>
                  </div>
                  <h6 class="text-muted font-weight-normal">Pair sold today</h6>
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
                        <canvas id="areaChart" style="height:250px"></canvas>
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