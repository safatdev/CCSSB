<?php

class Post {

	private $con;
	public $error;

	// The Constructor
	public function __construct($con) {
		$this->con = $con;
		$this->reqId = $_SESSION['uid'];
	}


	// get single Post
	public function getPost($pid) {
		
		// query
		$q = "SELECT p.*, u.`user_id`, u.`uname`, u.`fname` u FROM `posts` p JOIN `users` u ON p.`author_id` = u.`user_id` WHERE p.`post_id` = $pid";

		// executing
		if ($r = $this->con->query($q)) {

			$f = $r->fetch_assoc();

			// check if empty
			if (!empty($f)) {
				$f['author_image'] = IMG.'user.png';
				
				// amount of likes for this post
				$likes = $this->getPostLikes($f['post_id']);
				$f['post_likes'] = $likes;
				
				// return whether current user liked the post
				$liked = $this->userLiked($f['post_id']);
				$f['user_liked'] = $liked;
				
				return $f;
			} else {
				$this->error("This Post does not exists.");
			}

		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}
	}


	// Get all Posts
	public function getAllPosts($page, $amount) {
		$query = "JOIN `users` u ON p.`author_id` = u.`user_id`";
		return $this->getPosts($query, $page, $amount);
	}


	// Get all followers Post [is used in the timeline]
	public function getUserPosts($uid, $page, $amount) {
		$query = "JOIN `users` u ON p.`author_id` = u.`user_id`
		WHERE p.`author_id` = $uid";

		return $this->getPosts($query, $page, $amount);
	}


	// Get all followers Post [is used in the timeline]
	public function getAllFollowingPosts($uid, $page, $amount) {
		$query = "JOIN `users` u ON p.`author_id` = u.`user_id`
		JOIN `follows` f ON p.`author_id` = f.`to_follow_id`
		WHERE f.`follower_id` = $uid";
		
		return $this->getPosts($query, $page, $amount);
	}



	// Toggles between like and unlike
	public function toggleLikePost($pid) {

		// Setting the Userid to session
		$uid = $_SESSION['uid'];

		$q = "SELECT `like_id` FROM `likes` WHERE `post_id` = $pid AND `user_id` = $uid";

		// query execution
		if ($r = $this->con->query($q)) {;

			$f = $r->fetch_assoc();

			// Checks if there is not like
			if (empty($f)) {
				if ($this->likePost($pid, $uid)) {
					return array('liked' => true);
				} else {
					return false;
				}
			} else {
				if ($this->unlikePost($pid, $uid)) {
					return array('liked' => false);
				} else {
					return false;
				}
			}

		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}
	}


	// Like a post
	private function likePost($pid, $uid) {

		// Query
		$q = "INSERT INTO `likes` (`post_id`, `user_id`) VALUES($pid, $uid)";

		// Executing
		if ($this->con->query($q)) {
			return true;
		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}
	}


	// Unlike a post
	private function unlikePost($pid, $uid) {

		// Query
		$q = "DELETE FROM `likes` WHERE `post_id` = $pid AND `user_id` = $uid";

		// Executing
		if ($this->con->query($q)) {
			return true;
		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}

	}


	// Comment on a post
	public function commentOnPost($pid, $uid, $comment) {
		
	}

	// Gets Posts with pagination
	private function getPosts($post_type_query, $page, $amount) {

		// Where the pagination will start
		$limit_start = ($page - 1) * $amount;

		// Get the Page Amount
		$q = "SELECT COUNT(`post_id`) as total_posts FROM `posts` p $post_type_query";

		// Checks if the query ran
		if ($r = $this->con->query($q)) {

			// Sets the total posts
			$f = $r->fetch_assoc();
			$total_posts = $f['total_posts'];

			// The query
			$q = "SELECT p.*, u.`uname` as author_uname, u.`fname` as author_fname FROM `posts` p $post_type_query ORDER BY p.`post_time` DESC LIMIT $limit_start, $amount";

			// result
			if ($r = $this->con->query($q)) {

				// Return array
				$a = array();
				$o['total_posts'] = $total_posts; // Some pagination return data
				$o['total_pages'] = ceil($total_posts/$amount); // Some pagination return data
				$o['posts_remaining'] = $total_posts - ($limit_start + $amount);

				// Adding the Rows to the array
				while ($row = $r->fetch_assoc()) {
						
					// Checks if author image exists
					$author_img = ASB.'images/profile_pictures/'.$row['author_uname'].'.png';
					if (file_exists($author_img)) {
						$author_img = IMG.'profile_pictures/'.$row['author_uname'].'.png';
					} else {
						$author_img = IMG.'user.png';
					}
					$row['author_image'] = $author_img;

					
					// amount of likes for this post
					$likes = $this->getPostLikes($row['post_id']);
					$row['post_likes'] = $likes;
					
					// return whether current user liked the post
					$liked = $this->userLiked($row['post_id']);
					$row['user_liked'] = $liked;

					// Return the whole post
					$a[] = $row;
				}

				// The posts output
				$o['posts'] = $a;
				return $o;

			} else {
				return $this->error("Something went wrong. Try again later." . $this->con->error);
			}

		} else {
			return $this->error("Something went wrong. Try again later.". $this->con->error);
		}
	}


	// Check whether user liked it
	private function userLiked($pid) {

		// The User id
		$uid = $_SESSION['uid'];

		// Get the total like amount
		$q = "SELECT `like_id` FROM `likes` WHERE `post_id` = $pid AND `user_id` = $uid";

		// Query execution
		if ($r = $this->con->query($q)) {

			// sqlite_fetch_string()
			$f = $r->fetch_assoc();

			// checks if f is empty or not
			if (!empty($f)) {
				return true;
			} else {
				return false;
			}

		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}

	}


	// Get the likes amount
	private function getPostLikes($pid) {

		// Get the total like amount
		$q = "SELECT COUNT(`like_id`) AS post_likes FROM `likes` WHERE `post_id` = $pid";

		// Query execution
		if ($r = $this->con->query($q)) {

			$f = $r->fetch_assoc();
			return $f['post_likes'];

		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}

	}


	// delete a post
	public function deletePost($pid) {

		// query
		$q = "DELETE FROM `posts` WHERE `post_id` = $pid";

		// executing
		if ($this->con->query($q)) {
			return true;
		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}
	}


	// edit a post
	public function updatePost($pid, $post_content) {

		// sanitizint
		$post_content = htmlspecialchars($this->con->escape_string($post_content));

		// query
		$q = "UPDATE `posts` SET `post_content` = '$post_content' WHERE `post_id` = $pid";

		// executing
		if ($this->con->query($q)) {
			return true;
		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}

	}


	// create a post
	public function createPost($post_content) {

		// loggedin user
		$uid = $_SESSION['uid'];

		// sanitizing
		$post_content = htmlspecialchars($this->con->escape_string($post_content));

		// query
		$q = "INSERT INTO `posts` (`post_content`, `post_time`, `author_id`) VALUES('$post_content', NOW(), $uid)";

		// executing
		if ($this->con->query($q)) {
			
			// get the id back
			$q = "SELECT `post_id` FROM `posts` ORDER BY `post_time` DESC LIMIT 1";

			// executing
			if ($r = $this->con->query($q)) {

				// fetching
				$f = $r->fetch_assoc();
				return $f['post_id'];

			} else {
				$this->error("Something went wrong. Please try again." . $this->con->error);
			}

		} else {
			$this->error("Something went wrong. Please try again." . $this->con->error);
		}

	}


	// The error
	private function error($e) {
		$this->error = $e;
		return false;
	}

}

?>