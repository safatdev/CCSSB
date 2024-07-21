<?php

import('send_mail');

class Verify {

	private $con;
	public $error;

	// Constructor
	public function __construct($con) {
		$this->con = $con;
	}


	// Send code to User's Email
	public function sendCodeByUsername($username) {

		// Get the user id and Email
		$user = new User($this->con);
		if ($user_info = $user->getUserInfoByUsername(array('user_id', 'email'), $username)) {
		
			// Create the Code
			if ($code = $this->createCode($user_info['user_id'])) {

				// Preparing the Mail
				$message = '<h1 style="margin-bottom:40px;color: #fff;">Welcome!</h1>
	    		<p style="margin-bottom:30px;color: #fff;">Thank you for registering at City Computer Science Study Buddy!</p>
	    		<span style="display: inline-block; padding: 20px 30px 20px 30px; background: #e85339; border-radius:30px;color: #fff;">Your Code is: <span style="color:#fff;">'.$code.'</span></span>';

				// Sending the Mail
				if (new SendMail($user_info['email'], 'noreply@safat.dev', 'Your CCSSB Verification Code', $message)) {
					return true;
				} else {
					return $this->error = 'Something went wrong, please try again';
				}
			
			} else {
				return false;
			}

		} else {
			return $this->error($user->error);
		}

	}


	// Resends the Code to the user
	public function resendCode() {

		// User id
		$uid = $_SESSION['uid'];

		// Delete the User's Current Code
		$q = "DELETE FROM `verification` WHERE `user_id`=$uid";

		// Checks if successful
		if ($r = $this->con->query($q)) {

			// Sending the Code to User's Email
			return $this->sendCode($uid);

		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}

	}


	// Verifies the User
	public function verifyCode($uid, $code) {

		// Sanitizing
		$code = $this->con->escape_string($code);

		// query
		$q = "SELECT `verify_id` FROM `verification` WHERE `user_id`=$uid AND `verify_code` = '$code'";

		// Result
		if ($r = $this->con->query($q)) {

			// Fetching the Data
			$f = $r->fetch_assoc();

			// check if empty
			if (!empty($f)) {

				// The Verification ID
				$verify_id = $f['verify_id'];

				// Deleting the Verification Code from the Database
				$q = "DELETE FROM `verification` WHERE `verify_id`=$verify_id;
					  UPDATE `users` SET `verified` = 1 WHERE `user_id`=$uid";

				// result
				if ($r = $this->con->multi_query($q)) {
					return true;
				} else {
					return $this->error("Something went wrong. Try again later." . $this->con->error);
				}

			} else {
				return $this->error("Sorry, we couldn't verify you. Try again, or resend the code maybe?");
			}

		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}

	}


	// Send code to User's Email
	private function sendCode($uid) {

		// Get the user id and Email
		$user = new User($this->con);
		if ($user_info = $user->getUserInfo(array('fname', 'uname', 'email'))) {
			
			// Checking if the User set their Full Name
			$u_name = (empty($user_info['fname']) ? $user_info['uname'] : $user_info['fname']);

			// Create the Code
			if ($code = $this->createCode($uid)) {

				// Preparing the Mail
				$message = '<h1 style="margin-bottom:40px;color: #fff;">Hello '.$u_name.'</h1>
	    		<p style="margin-bottom:30px;color: #fff;">You requested for your Code to be resent!</p>
	    		<span style="display: inline-block; padding: 20px 30px 20px 30px; background: #e85339; border-radius:30px;color: #fff;">Your Code is: <span style="color:#fff;">'.$code.'</span></span>';

				// Sending the Mail
				if (new SendMail($user_info['email'], 'noreply@safat.dev', 'Your CCSSB Verification Code', $message)) {
					return true;
				} else {
					return $this->error = 'Something went wrong, please try again';
				}
			
			} else {
				return false;
			}
		} else {
			return $this->error($user->error);
		}

	}


	// create code
	private function createCode($id) {
	
		// Generate Secret Code
		$code = rand(10000, 99999);

		// Query
		$q = "INSERT INTO `verification` (`verify_code`, `user_id`, `time_created`) VALUES ('$code', $id, NOW())";

		// Execute the Query
		if ($this->con->query($q)) {
			return $code;
		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}


	// Raises an error
	private function error($e) {
		$this->error = $e;
		return false;
	}


}

?>