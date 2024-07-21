<?php


// If the user is loggedin
if ($data['loggedin']) {

	// Getting Userinfo
	$user = new User($controller->con);

	// Getting the username
	if ($data['username'] = $user->getUserInfo('uname')) {
		echo $user->error;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<?php $controller->render('head', $data); ?>
</head>
<body>

	<!-- The Login Modal -->
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				
				<div class="modal-body">

					<h5 class="login-title" id="loginModalLabel">Login</h5>

					<form action="<?php echo HOME; ?>login" method="post">
						<span class="form-element">
							<input class="form-control" type="text" name="username" placeholder="username" required>
						</span>

						<span class="form-element">
							<input class="form-control" type="password" name="password" placeholder="password" required>
						</span>

						<a style="display:block;margin-top:-20px;" class="text-right" href="#">Forgot Password?</a>

						<button type="submit" class="btn btn-dark" name="submit">login</button>

					</form>
				</div>

				<div class="modal-footer">
					<a href="<?php echo HOME; ?>register">Don't have an account? Why not register!</a>
				</div>
			</div>
		</div>
	</div>
	<!-- END The Login Modal -->

	<!-- START The Wrapper for Gradient -->
	<div id="wrap">
		
		<!-- The Header -->
		<header>
			<nav class="navbar navbar-expand-lg">
				<div class="container">

					<div class="d-block d-lg-none">
						<a class="navbar-brand" href="#">CCSSB</a>
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ccssb-navbar" aria-controls="ccssb-navbar" aria-expanded="false" aria-label="Toggle navigation">
							<i class="fas fa-bars"></i>
						</button>
					</div>

					<div class="collapse navbar-collapse" id="ccssb-navbar">
						<ul class="navbar-nav ccssb-nav mr-auto">
							<li class="nav-item <?php echo ($data['cur_page'] == 'home') ? 'active' : ''; ?>">
								<a class="nav-link" href="<?php echo HOME; ?>">Home</a>
							</li>

							<li class="nav-item <?php echo ($data['cur_page'] == 'news') ? 'active' : ''; ?>">
								<a class="nav-link" href="<?php echo HOME; ?>news">News</a>
							</li>

							<li class="nav-item <?php echo ($data['cur_page'] == 'about') ? 'active' : ''; ?>">
								<a class="nav-link" href="<?php echo HOME; ?>about">About</a>
							</li>

							<li class="nav-item <?php echo ($data['cur_page'] == 'contact') ? 'active' : ''; ?>">
								<a class="nav-link" href="<?php echo HOME; ?>contact">Contact</a>
							</li>
						</ul>
						
						<ul class="navbar-nav ml-auto">

							<?php
							if (!$data['loggedin']) {
							?>

							<li class="nav-item <?php echo ($data['cur_page'] == 'login') ? 'active' : ''; ?>">
								<a href="<?php echo HOME; ?>login" class="btn btn-login" data-toggle="modal" data-target="#loginModal">Login</a>
							</li>

							<?php
							} else {
							?>

							<li class="nav-item">
								<a href="<?php echo HOME; ?>login" class="btn btn-login" data-toggle="modal" data-target="#loginModal">Hello <?php echo $data['username']; ?></a>
							</li>

							<?php
							}
							?>
						</ul>

					</div>

				</div>
			</nav>
		</header>

		<!-- Main content -->
		<div id="home-main">
			<div class="container">