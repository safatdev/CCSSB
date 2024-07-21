<?php

// Importing
import('verify');

// Preparing the Data
$data['title'] = 'Register';

// Check if User logged in
if ($data['loggedin']) {
	$controller->redirect('timeline');
}

// Login Processing
if (isset($_POST['submit'])) {

	// variables
	$username = $_POST['username'];
	$email = $_POST['email'];
	$uni_email = $_POST['uni_email'];
	$password = $_POST['password'];

	// Authenticate
	$auth = new Authenticate($controller->con);

	// Checks if the Registration was successful
	if ($auth->register($username, $email, $uni_email, $password)) {
		
		// Logs the User in by Default
		$auth->login($username, $password);
		
		// Create and send the Verification Code to the user
		$verify = new Verify($controller->con);
		
		if ($verify->sendCodeByUsername($username)) {

			// Follow me
			$user = new User($controller->con);
			if (!$user->followUser(1, $_SESSION['uid'])) {
				echo $user->error;
			}
			
			$controller->redirect('verify');
		} else {
			echo $verify->error;
		}

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
		<h3>Register</h3>
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
			<div class="row">
				<div class="col-6">
					<input class="form-control" type="text" name="email" placeholder="email" value="<?php echo (isset($email) ? $email : ''); ?>" required>
				</div>

				<div class="col-6">
					<select class="form-control" name="uni_email" required>
						<option>@city.ac.uk</option>
						<option disabled>@gre.ac.uk</option>
					</select>
				</div>
			</div>
		</span>

		<span class="form-element">
			<input class="form-control" type="password" name="password" placeholder="password" value="<?php echo (isset($password) ? $password : ''); ?>" required>
		</span>

		<br>
		<button type="submit" class="btn btn-dark float-right" name="submit">register</button>
	</form>
</div>



<?php
// Displaying the Footer
$controller->render('footer', $data);
?>