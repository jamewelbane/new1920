<?php
session_start();
require_once '../database/connection.php';
require("function/check-login.php");

$isLoggedIn = 0;
if (!check_login_user_universal($link)) {

    $isLoggedIn = 0;
} else {
    $verifiedUID = $_SESSION['userid'];
    $isLoggedIn = 1;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET['order_id'])) {
       header("Location: transactions");
       exit;
    }

    $order_id = $_GET['order_id'];
    $pending_cancellation = 0;


    $checkOrderQuery = "SELECT * FROM cancellation_request WHERE userid = ? AND order_id = ? AND status = ?";
    $stmtOrder = $link->prepare($checkOrderQuery);
    $stmtOrder->bind_param("iii", $verifiedUID, $order_id, $pending_cancellation);
    $stmtOrder->execute();
    $resultOrder = $stmtOrder->get_result();
    $orderInfo = $resultOrder->fetch_assoc();
    $stmtOrder->close();

    if ($orderInfo) {
        // Order exists in cancellation_request table
        echo json_encode(['success' => false, 'message' => 'Cancellation request already exists for this order.']);
        // exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include("head.html"); ?>

<body>



    <?php

    include 'html/pre-loader.html';
    include("navbar.php");

    ?>

    <!-- search area -->
    <div class="search-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <span class="close-btn"><i class="fas fa-window-close"></i></span>
                    <div class="search-bar">
                        <div class="search-bar-tablecell">
                            <h3>Search For:</h3>
                            <input type="text" placeholder="Keywords">
                            <button type="submit">Search <i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end search arewa -->

    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Step Into Style</p>
                        <h1>Cancellation</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->


    <div class="full-height-section error-section">
        <div class="full-height-tablecell">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="error-text">

                            <?php
                            if ($orderInfo) {
                            ?>
                                <i class="far fa-smile"></i>
                                <h2>Cancellation</h2>

                                <p>You have already requested a cancellation for this order. Please wait for approval. Thank you!</p>
                                <a href="" class="boxed-btn">Return</a>
                            <?php
                            }
                            ?>

                            <i class="fas fa-shopping-cart"></i>
                            <img src="" alt="">
                            <h2>Cancellation Form</h2>

                            <form>
                                <div class="form-group">
                                    <textarea name="reason" id="reason" style="width: 100%" class="form_input" rows="4" placeholder="Please tell us why you want to cancel your order"></textarea>
                                    <input type="text" id="order_id_input" value="<?= $order_id ?>" hidden>
                                </div>
                                <button id="cancellationButton" class="boxed-btn">Submit</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



    <?php
    include 'html/logo-brand.html';
    include 'html/footer.php';
    include 'html/copyright.html';


    include 'injectables.html';
    ?>

    <script>
        window.onload = function() {
            var cartIcon = document.getElementById("transac");
            if (cartIcon) {
                cartIcon.style.color = "#F28123";
            }
        };
    </script>

<script>
		

		document.getElementById('cancellationButton').addEventListener('click', function() {
			if (confirm('Proceed with cancelling your order?')) {
				// Get the value of the note textarea
				var reason = document.getElementById('reason').value;
                var order_id_input = document.getElementById('order_id_input').value;
                				

				if (!reason) {
					alert('Field empty.');
					return; 
				}

				

				// Prepare form data to send to checkout script
				var formData = new FormData();
				formData.append('action', 'cancel-request');
				formData.append('reason', reason);
                formData.append('order_id_input', order_id_input);
			

				// Proceed with checkout
				fetch('function/cancel-request.php', {
						method: 'POST',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						alert(data.message);
						if (data.success) {
							// window.location.href = 'transactions';
                            console.log('Success');
						}
					})
					.catch(error => console.error('Error:', error));
			}
		});
	</script>

</body>

</html>