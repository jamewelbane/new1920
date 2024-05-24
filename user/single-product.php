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


?>
<!DOCTYPE html>
<html lang="en">
<?php require 'head.html'; ?>


<style>

	.size-box {
		display: inline-block;
		width: 40px;
		height: 40px;
		margin: 5px;
		border: 2px solid #ddd;
		cursor: pointer;
		text-align: center;
		line-height: 40px;
		font-weight: bold;
	}


	.size-box.selected {
		border-color: #000;
	}

	.sizes {
		margin-top: 10px;
	}
</style>

<body>
	<script>
		function selectBox(element) {
			let siblings = element.parentNode.children;
			for (let i = 0; i < siblings.length; i++) {
				siblings[i].classList.remove('selected');
			}
			element.classList.add('selected');
		}
	</script>

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
						<p>See more Details</p>
						<h1>Single Product</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- single product -->
	<div class="single-product mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<div class="single-product-img">
						<img src="assets/img/products/product-img-5.jpg" alt="">
					</div>
				</div>
				<div class="col-md-7">
					<div class="single-product-content">
						<h3>9060</h3>
					
						<p class="single-product-pricing"><span>Size: </span></p>
						<div class="sizes">
							<div class="size-box" onclick="selectBox(this)">41</div>
							<div class="size-box" onclick="selectBox(this)">42</div>
							<div class="size-box" onclick="selectBox(this)">44</div>
							<div class="size-box" onclick="selectBox(this)">45</div>
							<div class="size-box" onclick="selectBox(this)">45</div>
							<div class="size-box" onclick="selectBox(this)">45</div>
							<div class="size-box" onclick="selectBox(this)">45</div>
							<div class="size-box" onclick="selectBox(this)">45</div>
						</div>
						<p class="single-product-pricing">₱8,999</p>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta sint dignissimos, rem commodi cum voluptatem quae reprehenderit repudiandae ea tempora incidunt ipsa, quisquam animi perferendis eos eum modi! Tempora, earum.</p>
						<div class="single-product-form">
							<form action="index.html">
								<input type="number" placeholder="0">
							</form>
							<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
							<p><strong>Categories: </strong>Unisex</p>
						</div>
						<h4>Share:</h4>
						<ul class="product-share">
							<li><a href=""><i class="fab fa-facebook-f"></i></a></li>
							<li><a href=""><i class="fab fa-twitter"></i></a></li>
							<li><a href=""><i class="fab fa-google-plus-g"></i></a></li>
							<li><a href=""><i class="fab fa-linkedin"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end single product -->


		<!-- more products -->
		<div class="more-products mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">	
						<h3><span class="orange-text">Related</span> Products</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.html"><img src="assets/img/products/product-img-1.jpg" alt=""></a>
						</div>
						<h3>Strawberry</h3>
						<p class="product-price"><span>Per Kg</span> 85$ </p>
						<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.html"><img src="assets/img/products/product-img-2.jpg" alt=""></a>
						</div>
						<h3>Berry</h3>
						<p class="product-price"><span>Per Kg</span> 70$ </p>
						<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
					</div>
				</div>
				<div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.html"><img src="assets/img/products/product-img-3.jpg" alt=""></a>
						</div>
						<h3>Lemon</h3>
						<p class="product-price"><span>Per Kg</span> 35$ </p>
						<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end more products -->




	<?php
	include 'html/logo-brand.html';
	include 'html/footer.php';
	include 'html/copyright.html';


	include 'injectables.html';
	?>


	<script>
		window.onload = function() {
			var shopItem = document.getElementById("shop");
			if (shopItem) {
				shopItem.classList.add("current-list-item");
			}
		};
	</script>
</body>

</html>