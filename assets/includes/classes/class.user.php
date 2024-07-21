<?php

class User {

	private $con;
	public $error;

	// The constructor
	public function __construct($con) {
		$this->con = $con;
	}


	// Get User Information
	public function getUserInfo($info, $userid = false) {

		// Check If Userid given
		if (!$userid) {
			$userid = $_SESSION['uid'];
		}

		// select
		$select = "";

		// Check if Info is an array
		if (is_array($info)) {
			
			// if array, loop through
			foreach ($info as $i => $s) {
				$select = $select . "`" . $s . "`" . (((count($info)-1) != $i) ? "," : "");
			}

		} else {
			$select = "`".$info."`";
		}

		// The query
		$q = "SELECT $select FROM `users` WHERE `user_id` = $userid";

		// result
		if ($r = $this->con->query($q)) {
					
			// fetch
			$f = $r->fetch_assoc();

			// Checks if the 
			if (!empty($f)) {
				if (is_array($info)) {
					return $f;
				} else {
					return $f[$info];
				}
			} else {
				return $this->error("The requested Information wasn't found.");
			}
		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}


	// Get User Information
	public function getUserInfoByUsername($info, $uname) {

		// select
		$select = "";

		// Check if Info is an array
		if (is_array($info)) {
			
			// if array, loop through
			foreach ($info as $i => $s) {
				$select = $select . "`" . $s . "`" . (((count($info)-1) != $i) ? "," : "");
			}

		} else {
			$select = "`".$info."`";
		}

		// The query
		$q = "SELECT $select FROM `users` WHERE `uname` = '$uname'";

		// result
		if ($r = $this->con->query($q)) {
					
			// fetch
			$f = $r->fetch_assoc();
				
			// Checks if the 
			if (!empty($f)) {
				if (is_array($info)) {
					return $f;
				} else {
					return $f[$info];
				}

			} else {
				return $this->error("The requested Information wasn't found.");
			}
		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}



	// check if user is following
	public function isFollowingUser($to_follow_id) {

		// logged in user
		$uid = $_SESSION['uid'];

		// Query
		$q = "SELECT `follow_id` FROM `follows` WHERE `to_follow_id` = $to_follow_id AND `follower_id` = $uid";

		// Executing
		if ($r = $this->con->query($q)) {

			// fetch
			$f = $r->fetch_assoc();

			// if following
			if (!empty($f)) {
				return true;
			} else {
				return false;
			}

		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}



	}


	// Toggle Follow User
	public function toggleFollowUser($to_follow_id) {

		// logged in user
		$uid = $_SESSION['uid'];

		// Query
		$q = "SELECT `follow_id` FROM `follows` WHERE `to_follow_id` = $to_follow_id AND `follower_id` = $uid";

		// Executing
		if ($r = $this->con->query($q)) {

			// fetch
			$f = $r->fetch_assoc();

			// if following
			if (empty($f)) {
				if ($this->followUser($to_follow_id, $uid)) {
					return array('following' => true);
				} else {
					return false;
				}
			} else {
				if ($this->unfollowUser($to_follow_id, $uid)) {
					return array('following' => false);
				} else {
					return false;
				}
			}

		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}


	// Follow a User [to follow] [the user that's following]
	public function followUser($to_follow_id, $follower_id) {
		
		// Query
		$q = "INSERT INTO `follows` (`to_follow_id`, `follower_id`) VALUES ($to_follow_id, $follower_id)";

		// Executing
		if ($this->con->query($q)) {
			return true;
		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}


	// Follow a User [to follow] [the user that's following]
	public function unfollowUser($to_follow_id, $follower_id) {
		
		// Query
		$q = "DELETE FROM `follows` WHERE `to_follow_id` = $to_follow_id AND `follower_id` = $follower_id";

		// Executing
		if ($this->con->query($q)) {
			return true;
		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}


	// Get User Followers Amount
	public function getUserFollowersAmount($uid) {

		// Query
		$q = "SELECT COUNT(u.`user_id`) AS followers_amount FROM `users` u RIGHT JOIN `follows` f ON u.`user_id` = f.`follower_id` WHERE f.`to_follow_id` = $uid";

		// Executing
		if ($r = $this->con->query($q)) {
				
			// Return array
			$f = $r->fetch_assoc();

			// Checks if the result is empty or not
			if (!empty($f)) {
				return $f['followers_amount'];
			}

		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}


	// Get User Followers Amount
	public function getUserFollowingAmount($uid) {

		// Query
		$q = "SELECT COUNT(u.`user_id`) AS followers_amount FROM `users` u RIGHT JOIN `follows` f ON u.`user_id` = f.`to_follow_id` WHERE f.`follower_id` = $uid";

		// Executing
		if ($r = $this->con->query($q)) {
				
			// Return array
			$f = $r->fetch_assoc();

			// Checks if the result is empty or not
			if (!empty($f)) {
				return $f['followers_amount'];
			}

		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}
	}


	// Get User's followers
	public function getUserFollowers($uid) {

		// Query
		$q = "SELECT u.`user_id`, u.`uname` FROM `users` u RIGHT JOIN `follows` f ON u.`user_id` = f.`follower_id` WHERE f.`to_follow_id` = $uid";

		// Executing
		if ($r = $this->con->query($q)) {
				
			// Return array
			$o = array();

			// Adding the Rows to the array
			while ($row = $r->fetch_assoc()) {
				$o[] = $row;
			}

			// Checks if the result is empty or not
			if (!empty($o)) {
				return $o;
			} else {
				return $this->error("User has no followers.");
			}

		} else {
			return $this->error("Something went wrong. Try again later." . $this->con->error);
		}

	}


	// Get User's following
	public function getUserFollowing($uid) {

		// Query
		$q = "SELECT u.`user_id`, u.`uname` FROM `users` u RIGHT JOIN `follows` f ON u.`user_id` = f.`to_follow_id` WHERE f.`follower_id` = $uid";

		// Executing
		if ($r = $this->con->query($q)) {
			
			// Return array
			$o = array();

			// Adding the Rows to the array
			while ($row = $r->fetch_assoc()) {
				$o[] = $row;
			}

			// Checks if the result is empty or not
			if (!empty($o)) {
				return $o;			
			} else {
				return $this->error("User does not follow anyone.");
			}

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