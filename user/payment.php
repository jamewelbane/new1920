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


// Fetch user payment details
$isActive = 1;
$payQuery = "SELECT account_number FROM payment_details WHERE isActive = ?";
$stmtPay = $link->prepare($payQuery);
$stmtPay->bind_param("i", $isActive);
$stmtPay->execute();
$resultPay = $stmtPay->get_result();
$payDetails = $resultPay->fetch_assoc();
$stmtPay->close();


// Fetch user information
$userQuery = "SELECT name, address, email, phone_number FROM user_info WHERE user_id = ?";
$stmtUser = $link->prepare($userQuery);
$stmtUser->bind_param("i", $verifiedUID);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$userInfo = $resultUser->fetch_assoc();
$stmtUser->close();

// Fetch cart items
$cartQuery = "SELECT product_id, prod_size, quantity FROM cart WHERE userid = ?";
$stmtCart = $link->prepare($cartQuery);
$stmtCart->bind_param("i", $verifiedUID);
$stmtCart->execute();
$resultCart = $stmtCart->get_result();

$cart_items = [];
while ($row = $resultCart->fetch_assoc()) {
	$cart_items[] = $row;
}
$stmtCart->close();


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
											<form id="orderForm">
												<p><input type="text" value="<?php echo htmlspecialchars($userInfo['name']); ?>" readonly></p>
												<p><input type="email" value="<?php echo htmlspecialchars($userInfo['email']); ?>" readonly></p>
												<p><input type="text" value="<?php echo htmlspecialchars($userInfo['address']); ?>" readonly></p>
												<p><input type="tel" value="<?php echo htmlspecialchars($userInfo['phone_number']); ?>" readonly></p>
												<p><textarea name="note" id="note" cols="30" rows="10" placeholder="Note"></textarea></p>
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
											<p style="font-weight: bold">Payment instruction</p>
											<p>1. <span style="font-weight: bold">Open GCash App:</span> Log in with your mobile number and PIN.</p>
											<p>2. <span style="font-weight: bold">Send Money:</span> Tap 'Send Money' > 'Express Send'.</p>
											<p>3. <span style="font-weight: bold">Enter Details:</span> Send payment to "<?php echo htmlspecialchars($payDetails['account_number']); ?>" and confirm.</p>
											<p>4. <span style="font-weight: bold">Screenshot:</span> Take a screenshot of the transaction receipt.</p>
											<p>5. <span style="font-weight: bold">Upload Receipt:</span> Upload the screenshot for order confirmation.</p>
											<p style="margin-top: 10px; margin-bottom:10px;"><small muted><i><span style="font-weight: bold">*Note:</span> Orders without a receipt will be cancelled.</i></small></p>
											<p style="margin-top: 10px; margin-bottom:10px;"><small muted><i><span style="font-weight: bold">*Note:</span> This payment does not include the shipping fee. An additional delivery fee will be required once your order is confirmed.</i></small></p>
											<p>Upload Proof of Payment:</p>
											<input type="file" id="proofOfPayment" accept="image/*">
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
									<th>Price</th>
								</tr>
							</thead>
							<tbody class="order-details-body">
								<?php
								$total_price = 0;
								foreach ($cart_items as $item) :

									$product_id = $item['product_id'];
									// Fetch product name
									$prod_nameQuery = "SELECT prod_name FROM products WHERE Product_id = ?";
									$stmtProd_name = $link->prepare($prod_nameQuery);
									$stmtProd_name->bind_param("i", $product_id);
									$stmtProd_name->execute();
									$resultProd_name = $stmtProd_name->get_result();
									$prod_name_row = $resultProd_name->fetch_assoc(); // Fetching the row
									$prod_name = $prod_name_row['prod_name']; // Extracting the product name
									$stmtProd_name->close();

									$price = getProductPrice($product_id); // Use the updated function to get the product price
									$row_total = $price * $item['quantity'];

									$total_price += $row_total;
								?>
									<tr>
										<td><?php echo htmlspecialchars($prod_name . '-' . $item['prod_size'] . ', ' . $item['quantity'] . ' pair'); ?></td>
										<td>₱<?php echo number_format($row_total, 2); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<tbody class="checkout-details">
								<tr>
									<td>Subtotal</td>
									<td>₱<?php echo number_format($total_price, 2); ?></td>
								</tr>
								<tr>
									<td>Total</td>
									<td>₱<?php echo number_format($total_price, 2); ?></td>
								</tr>
							</tbody>
						</table>
						<a class="boxed-btn" id="placeOrderButton">Place Order</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end check out section -->


	<script>
		var name; // Initialize the name variable

		document.getElementById('placeOrderButton').addEventListener('click', function() {
			if (confirm('Proceed with placing your order?')) {
				// Get the value of the note textarea
				var note = document.getElementById('note').value;

				// Get the proof of payment image file
				var proofOfPaymentFile = document.getElementById('proofOfPayment').files[0];

				if (!proofOfPaymentFile) {
					alert('Please upload a proof of payment file.');
					return; // Exit the function if no file is selected
				}

				// Generate a unique identifier for the image name
				var uniqueIdentifier = Date.now(); // Using timestamp as the unique identifier

				// Construct the image name with the unique identifier
				var imageName = 'proof_payment_' + uniqueIdentifier + '_' + proofOfPaymentFile.name;

				// Prepare form data to send to checkout script
				var formData = new FormData();
				formData.append('action', 'checkout');
				formData.append('note', note);
				formData.append('proof_of_payment', proofOfPaymentFile, imageName);

				// Proceed with checkout
				fetch('function/checkout.php', {
						method: 'POST',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						alert(data.message);
						if (data.success) {
							window.location.href = 'transactions';
						}
					})
					.catch(error => console.error('Error:', error));
			}
		});
	</script>





</body>
<?php
include 'html/logo-brand.html';
include 'html/footer.php';
include 'html/copyright.html';


include 'injectables.html';
?>

</html>

<?php
function getProductPrice($product_id)
{
	global $link;
	// Fetch the price from the products table
	$query = "SELECT price FROM products WHERE product_id = ?";
	$stmt = $link->prepare($query);
	$stmt->bind_param("i", $product_id);
	$stmt->execute();
	$stmt->bind_result($price);
	$stmt->fetch();
	$stmt->close();

	// Check if the product is on discount and get the discount rate
	$query = "SELECT onDiscount, Discount FROM product_data WHERE product_id = ?";
	$stmt = $link->prepare($query);
	$stmt->bind_param("i", $product_id);
	$stmt->execute();
	$stmt->bind_result($onDiscount, $discount);
	$stmt->fetch();
	$stmt->close();

	// Apply discount if the product is on sale
	if ($onDiscount == 1) {
		$price = $price - ($price * ($discount / 100));
	}

	return $price;
}
?>