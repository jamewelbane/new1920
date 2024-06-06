<?php
// Include the TCPDF library
require_once(__DIR__ . '/../../vendor/tcpdf/tcpdf.php');
require_once '../../database/connection.php';

// Function to get total pairs sold, total revenue, and transaction counts for a given date range
function getMonthlySalesData($link, $startDate, $endDate)
{
  $query = "SELECT transaction_number, order_status FROM orders WHERE DATE(updated_at) BETWEEN ? AND ?";
  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_bind_param($stmt, 'ss', $startDate, $endDate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $totalPairs = 0;
  $totalRevenue = 0;
  $completedTransactions = 0;
  $canceledTransactions = 0;

  while ($row = mysqli_fetch_assoc($result)) {
    $transactionNumber = $row['transaction_number'];

    if ($row['order_status'] == 'Completed') {
      $completedTransactions++;

      // Calculate total pairs and total revenue for completed transactions
      $quantityQuery = "SELECT SUM(quantity) AS total_quantity FROM order_list WHERE transaction_number = ?";
      $stmtQuantity = mysqli_prepare($link, $quantityQuery);
      mysqli_stmt_bind_param($stmtQuantity, "s", $transactionNumber);
      mysqli_stmt_execute($stmtQuantity);
      $resultQuantity = mysqli_stmt_get_result($stmtQuantity);
      $rowQuantity = mysqli_fetch_assoc($resultQuantity);
      $totalPairs += $rowQuantity['total_quantity'];

      $revenueQuery = "SELECT SUM(total_amount) AS total_revenue FROM orders WHERE transaction_number = ?";
      $stmtRevenue = mysqli_prepare($link, $revenueQuery);
      mysqli_stmt_bind_param($stmtRevenue, "s", $transactionNumber);
      mysqli_stmt_execute($stmtRevenue);
      $resultRevenue = mysqli_stmt_get_result($stmtRevenue);
      $rowRevenue = mysqli_fetch_assoc($resultRevenue);
      $totalRevenue += $rowRevenue['total_revenue'];

      mysqli_free_result($resultQuantity);
      mysqli_stmt_close($stmtQuantity);
      mysqli_free_result($resultRevenue);
      mysqli_stmt_close($stmtRevenue);
    } elseif ($row['order_status'] == 'Cancelled') {
      $canceledTransactions++;
    }
  }

  mysqli_free_result($result);
  mysqli_stmt_close($stmt);

  return [
    'total_pairs' => $totalPairs,
    'total_revenue' => $totalRevenue,
    'completed_transactions' => $completedTransactions,
    'canceled_transactions' => $canceledTransactions,
  ];
}

// Get the previous month's date range
$previousMonth = date('m', strtotime('first day of last month'));
$previousYear = date('Y', strtotime('first day of last month'));
$startDate = date('Y-m-01', strtotime('first day of last month'));
$endDate = date('Y-m-t', strtotime('last day of last month'));

// Get data for the previous month
$data = getMonthlySalesData($link, $startDate, $endDate);
$totalPairsSold = $data['total_pairs'];
$totalRevenue = $data['total_revenue'];
$completedTransactions = $data['completed_transactions'];
$canceledTransactions = $data['canceled_transactions'];

// Calculate additional statistics
$totalDays = date('t', strtotime('last month'));
$averageEarningsPerDay = $totalRevenue / $totalDays;
$averagePairsSoldPerDay = $totalPairsSold / $totalDays;

// Create a new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle("Sales Report for the Month of " . date('F', strtotime('first day of last month')));
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('Sales, Report, Previous Month');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add title
$pdf->Cell(0, 10, 'Sales Report for the Month of ' . date('F', strtotime('first day of last month')), 0, 1, 'C');
$pdf->Ln(10);

// Add data to the table
$pdf->Cell(60, 10, 'Total Sold Pairs:', 0, 0);
$pdf->Cell(60, 10, $totalPairsSold, 0, 1);

$pdf->Cell(60, 10, 'Total Revenue:', 0, 0);
$pdf->Cell(60, 10, '₱' . number_format($totalRevenue, 2), 0, 1);

$pdf->Cell(60, 10, 'Average Earnings Per Day:', 0, 0);
$pdf->Cell(60, 10, '₱' . number_format($averageEarningsPerDay, 2), 0, 1);

$pdf->Cell(60, 10, 'Average Pairs Sold Per Day:', 0, 0);
$pdf->Cell(60, 10, number_format($averagePairsSoldPerDay, 2), 0, 1);

$pdf->Cell(60, 10, 'Completed Transactions:', 0, 0);
$pdf->Cell(60, 10, $completedTransactions, 0, 1);

$pdf->Cell(60, 10, 'Canceled Transactions:', 0, 0);
$pdf->Cell(60, 10, $canceledTransactions, 0, 1);

// Output the PDF to the browser
$pdf->Output('sales_report_previous_month.pdf', 'I');

// Close the connection
mysqli_close($link);
?>
