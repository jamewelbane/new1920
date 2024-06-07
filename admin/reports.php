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

$query_categories = "SELECT CategoryID, CategoryName FROM category";
$result_categories = mysqli_query($link, $query_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);


$query_size = "SELECT size_id, sizes_all FROM sizes";
$result_size = mysqli_query($link, $query_size);
$sizes = mysqli_fetch_all($result_size, MYSQLI_ASSOC);

?>

<body>

    <style>
        .form-vertical {
            display: flex;
            flex-direction: column;
            width: 200px;
            /* Adjust the width as needed */
        }

        .form-control {
            margin-bottom: 10px;
            /* Adjust the spacing between elements as needed */
        }

        .btn-primary {
            align-self: flex-start;
            /* Align the button to the start of the form */
        }
    </style>

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
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Sales Report</h4>
                                    <p class="card-description">*Use the buttons below to generate sales reports</p>

                                    <!-- Form to generate sales report for a specific month -->
                                    <form id="specificMonthReportForm" method="GET" action="function/generate_specific_report.php">
                                        <div class="d-flex">
                                            <select class="form-control mr-2" id="monthSelect" name="month" style="color: white;">
                                                <option value="01">January</option>
                                                <option value="02">February</option>
                                                <option value="03">March</option>
                                                <option value="04">April</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">August</option>
                                                <option value="09">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                            <select class="form-control mr-2" id="yearSelect" name="year" style="color: white;">
                                                <!-- Add options for years dynamically in JavaScript -->
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Generate Specific Month Report</button>

                                    </form>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Populate year dropdown with the last 10 years
            const yearSelect = document.getElementById('yearSelect');
            const currentYear = new Date().getFullYear();
            for (let i = 0; i < 10; i++) {
                const yearOption = document.createElement('option');
                yearOption.value = currentYear - i;
                yearOption.textContent = currentYear - i;
                yearSelect.appendChild(yearOption);
            }


        });
    </script>


</body>

</html>