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

$isCartEmpty = 1;

$checkCart = "SELECT * FROM cart WHERE userid = $verifiedUID";
$resultcheckCart = mysqli_query($link, $checkCart);


if (mysqli_num_rows($resultcheckCart) > 0) {
	$isCartEmpty = 0;
}

mysqli_free_result($resultcheckCart);
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.html'; ?>
<style>
	.delete-cart {
		background: transparent;

		border: none;

		color: inherit;

		padding: 10px;

		cursor: pointer;

	}
</style>

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
						<p>Checkout, Complete, Enjoy!</p>
						<h1>Cart</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->


	<?php

	if ($isCartEmpty === 0) { ?>
		<!-- cart -->
		<div class="cart-section mt-150 mb-150">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-12">
						<div class="cart-table-wrap">
							<table class="cart-table">
								<thead class="cart-table-head">
									<tr class="table-head-row">
										<th class="product-remove"></th>
										<th class="product-image">Product Image</th>
										<th class="product-name">Name</th>
										<th class="product-price">Price</th>
										<th class="product-size">Size</th>
										<th class="product-quantity">Quantity</th>
										<th class="product-row-total">Total</th>
									</tr>
								</thead>
								<tbody id="cart-items-tbody">
									<!-- Cart items will be dynamically inserted here -->
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="total-section">
							<table class="total-table">
								<thead class="total-table-head">
									<tr class="table-total-row">
										<th>Total</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>
									<tr class="total-data">
										<td><strong>Subtotal: </strong></td>
										<td id="subtotal">₱0.00</td>
									</tr>
									<tr class="total-data">
										<td><strong>Shipping: </strong></td>
										<td>₱60.00</td>
									</tr>
									<tr class="total-data">
										<td><strong>Total: </strong></td>
										<td id="total">₱60.00</td>
									</tr>
								</tbody>
							</table>
							<div class="cart-buttons">
								<a onclick="window.location.reload()" class="boxed-btn">Update Cart</a>
								<a class="boxed-btn black">Check Out</a>
							</div>
						</div>

						<div class="coupon-section">
							<h3>Apply Coupon</h3>
							<div class="coupon-form-wrap">
								<form action="index.html">
									<p><input type="text" placeholder="Coupon"></p>
									<p><input type="submit" style="color: white;" value="Apply"></p>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end cart -->

	<?php
	} else {
	?>
		<!-- error section -->
		<div class="full-height-section error-section">
			<div class="full-height-tablecell">
				<div class="container">
					<div class="row">
						<div class="col-lg-8 offset-lg-2 text-center">
							<div class="error-text">
								<i class="fas fa-shopping-cart"></i>
								<h1>Your cart is empty.</h1>
								<p>Check out our latest collection of trendy sneakers!</p>
								<a href="shop" class="boxed-btn">Shop Now</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end error section -->
	<?php } ?>


	<?php
	include 'html/logo-brand.html';
	include 'html/footer.php';
	include 'html/copyright.html';


	include 'injectables.html';
	?>

	<script>
		function attachDeleteEventListeners() {
			const deleteButtons = document.querySelectorAll('.delete-cart');

			deleteButtons.forEach(button => {
				button.addEventListener('click', function() {
					const cart_id = this.getAttribute('data-id');
					const confirmDelete = confirm('You are about to delete this item.\nAre you sure?');
					if (confirmDelete) {
						const xhr = new XMLHttpRequest();
						xhr.open('POST', 'function/delete-cart.php', true);
						xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						xhr.onreadystatechange = function() {
							if (xhr.readyState === 4 && xhr.status === 200) {
								alert('Deleted!\n' + xhr.responseText);
								fetchCartItems();
							}
						};
						xhr.send('cart_id=' + cart_id);
					}
				});
			});
		}

		function fetchCartItems() {
			var xhr = new XMLHttpRequest();
			xhr.open("GET", "function/fetch-cart.php", true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState === 4 && xhr.status === 200) {
					var response = JSON.parse(xhr.responseText);
					if (response.status === 'success') {
						var cartItems = response.data;
						var tbody = document.getElementById('cart-items-tbody');
						tbody.innerHTML = ''; // Clear existing content

						var subtotal = 0.00;

						cartItems.forEach(function(item) {
							var priceContent = item.discounted_price != item.original_price ?
								`<span style="text-decoration: line-through;">₱${item.original_price}</span> ₱${item.discounted_price}` :
								`₱${item.original_price}`;

							var tr = document.createElement('tr');
							tr.classList.add('table-body-row');
							tr.innerHTML = `
                                <td class="product-remove"><button class='delete-cart' data-id="${item.cart_id}"><i class='fas fa-trash-alt'></i></button></td>
                                <td class="product-image"><img src="${item.ImageURL}" alt=""></td>
                                <td class="product-name">${item.prod_name}</td>
                                <td class="product-price">${priceContent}</td>
                                <td class="product-size">${item.prod_size}</td>
                                <td class="product-quantity"><input type="number" value="${item.quantity}" min="1" max="10" onchange="updateQuantity(${item.cart_id}, ${item.product_id}, this.value)"></td>
                                <td class="product-row-total">₱${item.row_total}</td>
                            `;

							tbody.appendChild(tr);

							subtotal += parseFloat(item.row_total.replace(/[^0-9.-]+/g, ""));
						});

						var shippingFee = 60.00;
						var total = subtotal + shippingFee;
						document.getElementById('subtotal').innerText = `₱${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
						document.getElementById('total').innerText = `₱${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

						// Attach delete button event listeners
						attachDeleteEventListeners();
					} else {
						alert(response.message);
					}
				}
			};
			xhr.send();
		}

		window.onload = function() {
			var cartIcon = document.getElementById("cart");
			if (cartIcon) {
				cartIcon.style.color = "#F28123";
			}
		};

		document.addEventListener("DOMContentLoaded", function() {
			fetchCartItems();
		});

		window.updateQuantity = function(cartId, productId, quantity) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "function/update-cart.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function() {
				if (xhr.readyState === 4 && xhr.status === 200) {
					var response = JSON.parse(xhr.responseText);
					if (response.status === 'success') {
						fetchCartItems(); // Refresh cart items to reflect the updated quantity
					} else {
						alert(response.message);
						fetchCartItems();
					}
				}
			};
			xhr.send("cart_id=" + cartId + "&product_id=" + productId + "&quantity=" + quantity);
		}
	</script>
</body>

</html>