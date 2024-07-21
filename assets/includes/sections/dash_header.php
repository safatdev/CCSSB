<!DOCTYPE html>
<html>
<head>
	<?php $controller->render('dash_head', $data); ?>
</head>
<body>

	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="<?php echo HOME; ?>timeline">City CSSB</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ccssb_dash_nav" aria-controls="ccssb_dash_nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="ccssb_dash_nav">
				<ul class="navbar-nav ml-auto">

					<li class="nav-item <?php echo ($data['cur_page'] == 'timeline') ? 'active' : ''; ?>">
						<a class="nav-link" href="<?php echo HOME; ?>timeline"><i class="fas fa-stream"></i> Home</a>
					</li>

					<li class="nav-item <?php echo ($data['cur_page'] == 'profile') ? 'active' : ''; ?>">
						<a class="nav-link" href="<?php echo HOME.'profile'; ?>"><i class="fas fa-user"></i> Profile</a>
					</li>

					<li class="nav-item <?php echo ($data['cur_page'] == 'messages') ? 'active' : ''; ?>">
						<a class="nav-link" href="<?php echo HOME; ?>messages"><i class="fas fa-inbox"></i> Messages</a>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo $data['displayname']; ?>
						</a>

						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="#">Followers</a>
							<a class="dropdown-item" href="#">Following</a>
							<a class="dropdown-item" href="#">Settings</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo HOME.'logout'; ?>">Logout</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<!-- End Navbar -->

	<!-- The Container -->
	<div class="container">