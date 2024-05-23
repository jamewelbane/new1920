	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="../index">
								<img src="../assets/img/logo.png" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
						<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="#">Home</a>
									<ul class="sub-menu">
										<li><a href="index.html">Static Home</a></li>
										<li><a href="index_2.html">Slider Home</a></li>
									</ul>
								</li>
								<li><a href="about.html">About</a></li>


								<li><a href="contact.html">Contact</a></li>
								<li><a href="shop.php">Shop</a>
								</li>
								<li>
									<div class="header-icons">
										<a class="mobile-hide login-icon" data-toggle="modal" data-target="#loginModal"><i class="fas fa-user"></i></a>
										<a class="shopping-cart" href="cart"><i class="fas fa-shopping-cart"></i></a>
										<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>

									</div>
								</li>
							</ul>
						</nav>
						<a class="mobile-show login-icon" data-toggle="modal" data-target="#loginModal"><i class="fas fa-user"></i></a>
						<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
						<div class="mobile-menu"></div>
						<!-- menu end -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end header -->

	<style>
		.modal-header {

			align-items: right;
		}
	</style>

	<!-- Modal LOG IN-->
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title" id="loginModalLabel">Login</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Login form goes here -->
					<form action="user/function/login-process.php" method="post">
						<div class="form-group">
							<label for="username">Username or email</label>
							<input type="text" class="form-control" id="user" name="loginusername" placeholder="Enter your username or email" required>
						</div>
						<div class="form-group">
							<label class="details">Password</label>
							<div style="display: grid; grid-template-columns: 1fr auto;">
								<input type="password" id="LoginpasswordField" name="pass" placeholder="Enter your password" class="form-control" required>
								<button class="showPass" type="button" onclick="togglePasswordVisibility('LoginpasswordField', 'LoginpasswordToggleIcon')">
									<span class="far fa-eye" id="LoginpasswordToggleIcon"></span>
								</button>
							</div>
						</div>
						<button type="submit" class="btn btn-primary">Login</button>
					</form>
				</div>
			</div>
		</div>
	</div>