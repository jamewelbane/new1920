<?php
// Include the TCPDF library
require_once(__DIR__ . '/../../vendor/tcpdf/tcpdf.php');
require_once '../../database/connection.php';

// Get the selected month and year from the URL parameters
$selectedMonth = $_GET['month'];
$selectedYear = $_GET['year'];

// Function to get total pairs sold, total revenue, and transaction counts for a given date range
function getMonthlySalesData($link, $startDate, $endDate)
{
    $query = "SELECT transaction_number, order_status, updated_at FROM orders WHERE DATE(updated_at) BETWEEN ? AND ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $startDate, $endDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $totalPairs = 0;
    $totalRevenue = 0;
    $completedTransactions = 0;
    $canceledTransactions = 0;
    $detailedTransactions = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $transactionNumber = $row['transaction_number'];
        $updatedAt = $row['updated_at'];

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

            $detailedTransactions[] = [
                'date' => $updatedAt,
                'transaction_number' => $transactionNumber,
                'pairs_sold' => $rowQuantity['total_quantity'],
                'revenue' => $rowRevenue['total_revenue']
            ];

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
        'detailed_transactions' => $detailedTransactions
    ];
}

// Calculate the date range for the selected month and year
$startDate = date('Y-m-01', strtotime("$selectedYear-$selectedMonth-01"));
$endDate = date('Y-m-t', strtotime("$selectedYear-$selectedMonth-01"));

// Get data for the selected month
$data = getMonthlySalesData($link, $startDate, $endDate);
$totalPairsSold = $data['total_pairs'];
$totalRevenue = $data['total_revenue'];
$completedTransactions = $data['completed_transactions'];
$canceledTransactions = $data['canceled_transactions'];
$detailedTransactions = $data['detailed_transactions'];

// Calculate additional statistics
$totalDays = date('t', strtotime("$selectedYear-$selectedMonth-01"));
$averageEarningsPerDay = $totalRevenue / $totalDays;
$averagePairsSoldPerDay = $totalPairsSold / $totalDays;

// Create a new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle("Sales Report for " . date('F Y', strtotime("$selectedYear-$selectedMonth-01")));
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('Sales, Report, Selected Month');

// Add a page
$pdf->AddPage();

// Set font to DejaVuSans which supports the Peso symbol
$pdf->SetFont('dejavusans', '', 12);

    // Add title
    $pdf->Cell(0, 10, 'Sales Report for ' . date('F Y', strtotime("$selectedYear-$selectedMonth-01")), 0, 1, 'C');
    $pdf->Ln(10);

    // Add summary data
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

    $pdf->Ln(10); // Add some space before the table

    // Add detailed transactions table header
    $pdf->SetFillColor(240, 240, 240); // Set header background color
    $pdf->Cell(40, 10, 'Date', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Transaction Number', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Pairs Sold', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Revenue', 1, 1, 'C', true);

    // Add detailed transactions to the table
    foreach ($detailedTransactions as $transaction) {
        $pdf->Cell(40, 10, date('F d, Y', strtotime($transaction['date'])), 1);
        $pdf->Cell(50, 10, $transaction['transaction_number'], 1);
        $pdf->Cell(50, 10, $transaction['pairs_sold'], 1);
        $pdf->Cell(50, 10, '₱' . number_format($transaction['revenue'], 2), 1, 1);
    }

    // Output the PDF to the browser
    $pdf->Output('sales_report_' . date('F_Y', strtotime("$selectedYear-$selectedMonth-01")) . '.pdf', 'I');

    // Close the connection
    mysqli_close($link);
