<?php

// Importing the Password Hash Class
import('password_hash');

class Authenticate {

	private $con;
	private $auni;
	private $hash;
	public $error;


	// Establishes the Database Connection
	public function __construct($con) {
		$this->con = $con;
		$this->auni = unserialize(AVAILABLE_UNIS);
		$this->hash = new PasswordHash(8, FALSE);
	}


	// The login method
	public function login($ident, $password) {

		// Sanitizing the user input
		$ident = $this->con->escape_string($ident);
		$password = $this->con->escape_string($password);

		// fetch user password from the database
		$q = "SELECT `user_id`, `password` FROM `users` WHERE `uname` = '$ident' OR `email` = '$ident'";

		// Execute the Query
		if ($r = $this->con->query($q)) {

			// result
			$f = $r->fetch_assoc();

			// Check if it's not empty
			if (!empty($f)) {

				// variables
				$uid = $f['user_id'];
				$pass_hash = $f['password'];

				// If the password matches
				if ($this->hash->CheckPassword($password, $pass_hash)) {

					// Create the Session
					$_SESSION['uid'] = $uid;
					return true;

				} else {
					$this->error("Your Login Details seems to be wrong. Please try again.");
					return false;
				}

			} else {
				$this->error("Your Login Details seems to be wrong. Please try again.");
				return false;
			}

		} else {
			$this->error("Something went wrong. Try again later." . $this->con->error);
			return false;
		}
	}



	// The register method
	public function register($username, $email, $uni_email, $password) {

		// Sanitizing the user input
		$username = htmlspecialchars($this->con->escape_string($username));
		$email = $this->con->escape_string($email);
		$uni_email = $this->con->escape_string($uni_email);

		// Available Universities
		$um = false;
		foreach ($this->auni as $domain) {
			if ($uni_email == $domain) {
				$um = true;
				$email = $email . $uni_email;
			}
		}

		// Check if User inputted the right University Email
		if ($um) {

			// fetch user password from the database
			$q = "SELECT `uname`, `email` FROM `users` WHERE `uname` = '$username' OR `email` = '$email'";

			// Execute the Query
			if ($r = $this->con->query($q)) {

				// result
				$f = $r->fetch_assoc();

				// Checks if the Array returned empty
				if (empty($f)) {

					// Sanitizing the password and hashing it
					$password = $this->con->escape_string($password);
					$password = $this->hash->HashPassword($password);

					// Insert User into the Database
					$q = "INSERT INTO `users` (`uname`, `email`, `password`, `verified`, `join_date`) VALUES('$username', '$email', '$password', 0, NOW())";

					// Checks if the Entry was done correctly
					if ($r = $this->con->query($q)) {
						return true;
					} else {
						$this->error("Something went wrong. Try again later." . $this->con->error);
						return false;
					}

				} else {

					// Variables
					$d_user = $f['uname'];
					$d_email = $f['email'];

					// Checks which data already exists
					if ($username == $d_user && $email == $d_email) {
						$this->error("You are already registered. Please log in.");
					} else if ($username == $d_user) {
						$this->error("A User with that Username already exists.");
					} else if ($email == $d_email) {
						$this->error("A User with that Email already exists.");
					}

					return false;
				}
			} else {
				$this->error("Something went wrong. Try again later." . $this->con->error);
				return false;
			}

		} else {
			$this->error("Sorry, you cannot log in with this Email.");
			return false;
		}
	}


	// Checks if the user is logged in
	public function isLoggedIn() {

		// checks if the sessions exists
		if (isset($_SESSION['uid'])) {
			return true;
		} else {
			return false;
		}
	}


	// Check if the User is verified
	public function isVerified() {
		
		// Query
		$uid = $_SESSION['uid'];
		$q = "SELECT `verified` FROM `users` WHERE `user_id` = $uid";
			
		// Execute the Query
		if ($r = $this->con->query($q)) {

			// result
			$f = $r->fetch_assoc();

			if ($f['verified']) {
				return true;
			} else {
				return false;
			}

		} else {
			return false;
			$this->error("Something went wrong. Try again later." . $this->con->error);
		}

	}

	// logout
	public function logout() {
		session_destroy();
	}


	// Raises an error
	private function error($e) {
		$this->error = $e;
	}

}

?>