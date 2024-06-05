<?php
// require_once("../database/connection.php");
if ($verifiedUID === null) {
  header("location: ../../../index.php");
  exit;
}

// Query to count active and inactive users
$checkQuery = "SELECT 
                    (SELECT COUNT(*) FROM orders WHERE order_status = 'Pending') AS pending_orders, 
                    (SELECT COUNT(*) FROM orders WHERE order_status = 'Confirmed') AS pending_shipment";
$stmt = mysqli_prepare($link, $checkQuery);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$pendingOrdersCount = $row['pending_orders'];
$pendingShipmentCount = $row['pending_shipment'];

// Calculate the total active orders
$totalActiveOrders = $pendingOrdersCount + $pendingShipmentCount;

mysqli_free_result($result); // Free the result set

// Encode chart data as JSON
$chartData = json_encode(array(
  'labels' => ["Pending", "To ship"],
  'datasets' => array(
    array(
      'data' => array($pendingOrdersCount, $pendingShipmentCount),
      'backgroundColor' => ["#00d25b", "#ffab00"]
    )
  )
));

// Output chart data to JavaScript
echo "<script>localStorage.setItem('chartData', '$chartData');</script>";




// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');


$dailyRevenueQuery = "
    SELECT DATE(updated_at) as order_date, SUM(total_amount) as daily_revenue 
    FROM orders 
    WHERE order_status = 'Completed' AND MONTH(updated_at) = ? AND YEAR(updated_at) = ?
    GROUP BY DATE(updated_at)
    ORDER BY updated_at DESC
    LIMIT 7
";
$stmtDailyRevenue = mysqli_prepare($link, $dailyRevenueQuery);
mysqli_stmt_bind_param($stmtDailyRevenue, 'ii', $currentMonth, $currentYear);
mysqli_stmt_execute($stmtDailyRevenue);
$resultDailyRevenue = mysqli_stmt_get_result($stmtDailyRevenue);

$dates = [];
$revenues = [];

while ($rowDailyRevenue = mysqli_fetch_assoc($resultDailyRevenue)) {
  $dates[] = date('m/d', strtotime($rowDailyRevenue['order_date']));
  $revenues[] = $rowDailyRevenue['daily_revenue'];
}

mysqli_free_result($resultDailyRevenue);
mysqli_close($link);

// Reverse the arrays to display the dates in ascending order
$dates = array_reverse($dates);
$revenues = array_reverse($revenues);
?>



<script>
  (function($) {
    'use strict';
    $.fn.andSelf = function() {
      return this.addBack.apply(this, arguments);
    }
    $(function() {

      if ($("#transaction-history").length) {
        var areaData = {
          labels: ["Pending", "To ship"],
          datasets: [{
            data: [<?php echo $pendingOrdersCount; ?>, <?php echo $pendingShipmentCount; ?>],
            backgroundColor: [
              "#00d25b", "#ffab00"
            ]
          }]
        };


        var areaOptions = {
          responsive: true,
          maintainAspectRatio: true,
          segmentShowStroke: false,
          cutoutPercentage: 70,
          elements: {
            arc: {
              borderWidth: 0
            }
          },
          legend: {
            display: false
          },
          tooltips: {
            enabled: true
          }
        }
        var transactionhistoryChartPlugins = {
          beforeDraw: function(chart) {
            var width = chart.chart.width,
              height = chart.chart.height,
              ctx = chart.chart.ctx;

            ctx.restore();
            var fontSize = 1;
            ctx.font = fontSize + "rem sans-serif";
            ctx.textAlign = 'left';
            ctx.textBaseline = "middle";
            ctx.fillStyle = "#ffffff";

            var text = "<?php echo $totalActiveOrders ?>";
            var textX = Math.round((width - ctx.measureText(text).width) / 2);
            var textY = height / 2.4;

            ctx.fillText(text, textX, textY);

            ctx.restore();
            var fontSize = 0.75;
            ctx.font = fontSize + "rem sans-serif";
            ctx.textAlign = 'left';
            ctx.textBaseline = "middle";
            ctx.fillStyle = "#6c7293";

            var texts = "Total";
            var textsX = Math.round((width - ctx.measureText(texts).width) / 1.93);
            var textsY = height / 1.7;


            ctx.fillText(texts, textsX, textsY);
            ctx.save();
          }
        }
        var transactionhistoryChartCanvas = $("#transaction-history").get(0).getContext("2d");
        var transactionhistoryChart = new Chart(transactionhistoryChartCanvas, {
          type: 'doughnut',
          data: areaData,
          options: areaOptions,
          plugins: transactionhistoryChartPlugins
        });
      }



      // area chart




      var areaData = {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
          label: 'Daily Revenue for <?php echo date("F Y"); ?>',
          data: <?php echo json_encode($revenues); ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          fill: true,
        }]
      };

      // Render area chart
      if ($("#areaChart").length) {
        var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
        var areaChart = new Chart(areaChartCanvas, {
          type: 'line',
          data: areaData,
          options: {
            scales: {
              x: {
                type: 'time',
                time: {
                  unit: 'day'
                },
                title: {
                  display: true,
                  text: 'Date'
                }
              },
              y: {
                title: {
                  display: true,
                  text: 'Revenue (₱)'
                },
                ticks: {
                  callback: function(value, index, values) {
                    return '₱' + value.toLocaleString(); // Add commas for thousands
                  }
                }
              }
            }
          }
        });
      }


    });
  })(jQuery);
</script>