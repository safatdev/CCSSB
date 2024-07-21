<?php

/*
Preparing the Data
*/
$data['title'] = 'Login';

// Check if User logged in
if ($data['loggedin']) {
	$controller->redirect('timeline');
}

// Login Processing
if (isset($_POST['submit'])) {

	// variables
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Authenticate
	$auth = new Authenticate($controller->con);

	// logs the user in
	if ($auth->login($username, $password)) {
		$controller->redirect('timeline');
	} else {
		$error = $auth->error;
	}

}

// Displaying the Header
$controller->render('header', $data, $controller);

?>

<!-- Login page Content -->
<div class="card home-sign-up">

	<div class="card-title">
		<h3>Login</h3>
	</div>

	<?php
	if (isset($error)) {
		echo '<div class="alert alert-danger">'.$error.'</div>';
	}
	?>

	<form method="post">
		<span class="form-element">
			<input class="form-control" type="text" name="username" placeholder="username" value="<?php echo (isset($username) ? $username : ''); ?>" required>
		</span>

		<span class="form-element">
			<input class="form-control" type="password" name="password" placeholder="password" required>
		</span>
		
		<a style="display:block;margin-top:-20px;" class="text-right" href="#">forgot password?</a>

		<br>
		<button type="submit" class="btn btn-dark float-right" name="submit">login</button>
	</form>
</div>

<?php

$controller->render('footer', $data);
?>