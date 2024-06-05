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



    });
  })(jQuery);
</script>