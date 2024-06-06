<?php
// Include the TCPDF library
require_once(__DIR__ . '/../../vendor/tcpdf/tcpdf.php');

require_once '../../database/connection.php';

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

// Function to get daily sales data
function getDailySalesData($link, $date)
{
  $query = "SELECT COUNT(*) AS completed_transactions, 
                 (SELECT COUNT(*) FROM orders WHERE order_status = 'Cancelled' AND DATE(updated_at) = ?) AS canceled_transactions,
                 (SELECT SUM(quantity) FROM order_list WHERE transaction_number IN (SELECT transaction_number FROM orders WHERE DATE(updated_at) = ? AND order_status = 'Completed')) AS pairs_sold,
                 (SELECT SUM(total_amount) FROM orders WHERE DATE(updated_at) = ? AND order_status = 'Completed') AS earnings
            FROM orders WHERE DATE(updated_at) = ? AND order_status = 'Completed'";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_bind_param($stmt, "ssss", $date, $date, $date, $date);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  // Check if there are any results before fetching data
  if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
  } else {
    $data = [
      'completed_transactions' => null,
      'canceled_transactions' => null,
      'pairs_sold' => null,
      'earnings' => null,
    ];
  }

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  return $data;
}

// Get the selected month and year
$selectedMonth = $_GET['month'];
$selectedYear = $_GET['year'];

// Get the first and last date of the selected month
$startDate = date('Y-m-01', strtotime("$selectedYear-$selectedMonth-01"));
$endDate = date('Y-m-t', strtotime("$selectedYear-$selectedMonth-01"));

// Get total pairs sold
$totalPairsSold = getTotalPairsSold($link, "AND DATE(updated_at) >= '$startDate' AND DATE(updated_at) <= '$endDate'");

// Get total revenue
$queryTotalRevenue = "SELECT SUM(total_amount) AS total_revenue FROM orders WHERE DATE(updated_at) >= ? AND DATE(updated_at) <= ? AND order_status = 'Completed'";
$stmtTotalRevenue = mysqli_prepare($link, $queryTotalRevenue);
mysqli_stmt_bind_param($stmtTotalRevenue, "ss", $startDate, $endDate);
mysqli_stmt_execute($stmtTotalRevenue);
$resultTotalRevenue = mysqli_stmt_get_result($stmtTotalRevenue);
$rowTotalRevenue = mysqli_fetch_assoc($resultTotalRevenue);
$totalRevenue = $rowTotalRevenue['total_revenue'];

// Calculate average earnings per day
$diffInDays = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24); // Number of days in the month
$averageEarningsPerDay = $totalRevenue / $diffInDays;

// Calculate average pairs sold per day
$averagePairsSoldPerDay = ceil($totalPairsSold / $diffInDays);

// Get total completed transactions
$queryCompletedTransactions = "SELECT COUNT(*) AS completed_transactions FROM orders WHERE DATE(updated_at) >= ? AND DATE(updated_at) <= ? AND order_status = 'Completed'";
$stmtCompletedTransactions = mysqli_prepare($link, $queryCompletedTransactions);
mysqli_stmt_bind_param($stmtCompletedTransactions, "ss", $startDate, $endDate);
mysqli_stmt_execute($stmtCompletedTransactions);
$resultCompletedTransactions = mysqli_stmt_get_result($stmtCompletedTransactions);
$rowCompletedTransactions = mysqli_fetch_assoc($resultCompletedTransactions);
$completedTransactions = $rowCompletedTransactions['completed_transactions'];

// Get total cancelled transactions
$queryCanceledTransactions = "SELECT COUNT(*) AS canceled_transactions FROM orders WHERE DATE(updated_at) >= ? AND DATE(updated_at) <= ? AND order_status = 'Cancelled'";
$stmtCanceledTransactions = mysqli_prepare($link, $queryCanceledTransactions);
mysqli_stmt_bind_param($stmtCanceledTransactions, "ss", $startDate, $endDate);
mysqli_stmt_execute($stmtCanceledTransactions);
$resultCanceledTransactions = mysqli_stmt_get_result($stmtCanceledTransactions);
$rowCanceledTransactions = mysqli_fetch_assoc($resultCanceledTransactions);
$canceledTransactions = $rowCanceledTransactions['canceled_transactions'];

// Create a new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetFont('dejavusans', '', 10);
// Set document information
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle("Sales Report for " . date('F Y', strtotime("$selectedYear-$selectedMonth-01")));
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('Sales, Report, Selected Month');


// Add a page
$pdf->AddPage();



// Add title
$pdf->Cell(0, 10, 'Sales Report for ' . date('F Y', strtotime("$selectedYear-$selectedMonth-01")), 0, 1, 'C');
$pdf->Ln(10);

// Add data to the table
$pdf->Cell(60, 10, 'Total Pairs Sold:', 0, 0);
$pdf->Cell(60, 10, $totalPairsSold, 0, 1);

$pdf->Cell(60, 10, 'Total Revenue:', 0, 0);
$pdf->Cell(60, 10, '₱' . number_format($totalRevenue, 2), 0, 1);

$pdf->Cell(60, 10, 'Average Earnings Per Day:', 0, 0);
$pdf->Cell(60, 10, '₱' . number_format($averageEarningsPerDay, 2), 0, 1);

$pdf->Cell(60, 10, 'Average Pairs Sold Per Day:', 0, 0);
$pdf->Cell(60, 10, number_format($averagePairsSoldPerDay), 0, 1);

$pdf->Cell(60, 10, 'Total Completed Transactions:', 0, 0);
$pdf->Cell(60, 10, $completedTransactions, 0, 1);

$pdf->Cell(60, 10, 'Total Cancelled Transactions:', 0, 0);
$pdf->Cell(60, 10, $canceledTransactions, 0, 1);

// Add table for daily details
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Daily Details', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);


$pdf->SetFillColor(180, 180, 180); // Set fill color for table header

$pdf->Cell(30, 10, 'Date', 1, 0, 'C', true); // Add true as the last argument to Cell to fill the cell with the set fill color
$pdf->Cell(40, 10, 'Completed Transactions', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Cancelled Transactions', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Pairs Sold', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Earnings', 1, 1, 'C', true);
$pdf->SetFont('dejavusans', '', 10);
// Get daily data and add to the table
$currentDate = $startDate;
while ($currentDate <= $endDate) {
    $dailyData = getDailySalesData($link, $currentDate);

    // Check if any data exists for the current date
    if (array_filter($dailyData)) {
        $pdf->Cell(30, 10, $currentDate, 1, 0, 'C');
        $pdf->Cell(40, 10, $dailyData['completed_transactions'] ?? '-', 1, 0, 'C');
        $pdf->Cell(40, 10, $dailyData['canceled_transactions'] ?? '-', 1, 0, 'C');
        $pdf->Cell(40, 10, $dailyData['pairs_sold'] ?? '-', 1, 0, 'C');
        $pdf->Cell(40, 10, "\xE2\x82\xB1" . number_format($dailyData['earnings'] ?? 0, 2), 1, 1, 'C');
    }

    // Move to the next date
    $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
}


// Output the PDF to the browser
$pdf->Output('sales_report_selected_month.pdf', 'I');

// Close the connection
mysqli_close($link);
?>