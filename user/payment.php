<?php
session_start();
require_once '../database/connection.php';
require("function/check-login.php");



$isLoggedIn = 0;
if (!check_login_user_universal($link)) {

	$isLoggedIn = 0;
	header("Location: ../index");
	exit;
} else {
	$verifiedUID = $_SESSION['userid'];
	$isLoggedIn = 1;
}


$transaction_number = $_GET['transaction_number'];
$userid = $_GET['userid'];

// Fetch user information
$user_info_query = "SELECT name, address, email, phone_number FROM user_info WHERE user_id = ?";
$stmt_user_info = $link->prepare($user_info_query);
$stmt_user_info->bind_param("i", $userid);
$stmt_user_info->execute();
$user_info_result = $stmt_user_info->get_result();
$user_info = $user_info_result->fetch_assoc();
$stmt_user_info->close();

// Fetch order details
$order_list_query = "SELECT product_id, prod_size, quantity, total_price FROM order_list WHERE transaction_number = ?";
$stmt_order_list = $link->prepare($order_list_query);
$stmt_order_list->bind_param("s", $transaction_number);
$stmt_order_list->execute();
$order_list_result = $stmt_order_list->get_result();
$order_items = [];
while ($row = $order_list_result->fetch_assoc()) {
	// Fetch product name
	$product_id = $row['product_id'];
	$prod_name_query = "SELECT prod_name FROM products WHERE product_id = ?";
	$stmt_prod_name = $link->prepare($prod_name_query);
	$stmt_prod_name->bind_param("i", $product_id);
	$stmt_prod_name->execute();
	$result_prod_name = $stmt_prod_name->get_result();
	$prod_name_row = $result_prod_name->fetch_assoc(); // Fetching the row
	$prod_name = $prod_name_row['prod_name']; // Extracting the product name
	$stmt_prod_name->close();

	$row['prod_name'] = $prod_name; // Add product name to the row
	$order_items[] = $row;
}
$stmt_order_list->close();

$link->close();

?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.html'; ?>

<body>

	<?php

	include 'html/pre-loader.html';
	include("navbar.php");

	?>



	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Step Into Style</p>
						<h1>Check Out</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- check out section -->
	<div class="checkout-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="checkout-accordion-wrap">
						<div class="accordion" id="accordionExample">
							<div class="card single-accordion">
								<div class="card-header" id="headingOne">
									<h5 class="mb-0">
										<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
											Shipping Address
										</button>
									</h5>
								</div>
								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
									<div class="card-body">
										<div class="billing-address-form">
											<form>
												<p><input type="text" value="<?php echo htmlspecialchars($user_info['name']); ?>" readonly></p>
												<p><input type="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" readonly></p>
												<p><input type="text" value="<?php echo htmlspecialchars($user_info['address']); ?>" readonly></p>
												<p><input type="tel" value="<?php echo htmlspecialchars($user_info['phone_number']); ?>" readonly></p>
												<p><textarea name="order_note" id="bill" cols="30" rows="10" placeholder="Note"></textarea></p>
											</form>
										</div>
									</div>
								</div>
							</div>
							<div class="card single-accordion">
								<div class="card-header" id="headingThree">
									<h5 class="mb-0">
										<button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
											Payment
										</button>
									</h5>
								</div>
								<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
									<div class="card-body">
										<div class="card-details">
											<p>Your card details goes here.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="order-details-wrap">
						<table class="order-details">
							<thead>
								<tr>
									<th>Your Order Details</th>
									<th>Final price</th>
								</tr>
							</thead>
							<tbody class="order-details-body">
								<?php foreach ($order_items as $item) :

								?>
									<tr>
										<td><?php echo htmlspecialchars($item['prod_name'] . '-' . $item['prod_size'] . ', ' . $item['quantity'] . ' pair'); ?></td>
										<td>₱<?php echo number_format($item['total_price'], 2); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tbody class="checkout-details">
								<tr>
									<td>Subtotal</td>
									<td>₱<?php echo number_format(array_sum(array_column($order_items, 'total_price')), 2); ?></td>
								</tr>
								<tr>
									<td>Total</td>
									<td>₱<?php echo number_format(array_sum(array_column($order_items, 'total_price')), 2); ?></td>
								</tr>
							</tbody>
						</table>
						<a href="#" class="boxed-btn">Place Order</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end check out section -->








</body>
<?php
include 'html/logo-brand.html';
include 'html/footer.php';
include 'html/copyright.html';


include 'injectables.html';
?>

</html>