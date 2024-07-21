<?php

// Importing the Verify Class
import('verify');


// Check if User logged in and Verified
if (!$data['loggedin']) {
	$controller->redirect('login');
} else { if ($data['verified']) {
	$controller->redirect('timeline');
}}

// Passing of the Data
$data['title'] = 'Verification';

// User Details
$user = new User($controller->con);
if ($userinfo = $user->getUserInfo(array('uname', 'email'))) {
	$data['displayname'] = (!empty($userinfo['fname']) ? $userinfo['fname'] : $userinfo['uname']);
}

// Verify
$verify = new Verify($controller->con);

// Check for the User input
if (isset($_POST['verify'])) {

	// Verifies the Code
	if ($verify->verifyCode($data['user_id'], $_POST['secret_code'])) {
		$verified = true;
		$success = "Thank you for verifying your Email address.<br><br>Redirecting...";
	} else {
		$error = $verify->error;
	}


// Check if the User wants to resend the Code
} else if (isset($_POST['resend'])) {

	// Resends the Code
	if ($verify->resendCode()) {
		$success = "Your code has been resent!";
	} else {
		$error = $verify->error;
	}

}

// Rendering the header
$controller->render('dash_header', $data, $controller);
?>

<br><br>
<?php

// Displaying Error
if (!isset($verified)) {
	if (isset($error)) {
		echo '<div class="alert alert-danger">'.$error.'</div>';
	} else {
		echo '<div class="alert alert-danger">Hi! You haven\'t verified your Email. In order to use City Computer Science Study Buddy, you need to verify it!</div>';
	}
}

// Checking if the user requested a resend
if (isset($success)) {
	echo '<div class="alert alert-success">'.$success.'</div>';
}

// Checks if the User has verified
if (isset($verified)) {

	// Redirect the User to Timeline after 3 Seconds
	echo "<script type=\"text/javascript\">setTimeout(function(){
		window.location.replace('".HOME."timeline');
	}, 3000);</script>";
}
?>


<form style="overflow: hidden;" method="post">

	<p>We have sent a code to your Email: <b><?php echo $userinfo['email']; ?></b></p>
	<p><small>Sometimes this might take a while, also make sure to check your SPAM folder!</small></p>

	<br>

	<span class="form-element">
		<div class="form-group">
			<label>Enter your Code below:</label>
			<input class="form-control" type="text" name="secret_code" autocomplete="off">
		</div>
	</span>


	<input style="margin-left: 20px;" class="btn btn-primary float-right" type="submit" value="Verify" name="verify" required>
	<input class="btn btn-warning float-right" type="submit" value="Resend Code" name="resend" required>

</form>

<?php
// Rendering the Footer
$controller->render('dash_footer', $data);
?>