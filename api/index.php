<?php

// Requiring the config file
require_once '../config.php';

// Importing
import('db_con');
import('post');
import('user');

// The Output
$output = array(
	'success' => false,
	'error' => ''
);

// Chhecking if the User is loggedin
if (isset($_SESSION['uid'])) {

// User id
$uid = $_SESSION['uid'];

// Checking the Actions
if (isset($_POST['action']) && isset($_POST['param']) &&
	!empty($_POST['action']) && !empty($_POST['param'])) {

	// The Database Connection
	$db = new DBCon($db_info);
	$con = $db->con;

	// The action
	$action = $_POST['action'];
	$param = json_decode($_POST['param']);


	/*
	
	actions:
	loadPosts    |	takes: param = array('type' => 'user_posts | following_posts | all_posts', 'user_id', 'page' => int(page), 'amount' => int(amount)); | gives: array filled posts
	likePost     |	takes: param = array('post_id' => int(id)) | gives out boolean
	unlikePost   |	takes: param = array('post_id' => int(id)) | gives out boolean
	
	getMessages  |	
	sendMessage  |	

	*/


	// Load Posts Action
	if ($action == 'loadPosts') {

		// Post Class
		$post = new Post($con);

		// Setting the variables
		$type = $param->type;
		$page = $param->page;
		$amount = $param->amount;

		// Checks the type
		if ($type == 'user_posts') {

			// User id
			$author_id = $param->author_id;

			// if successful
			if ($posts = $post->getUserPosts($author_id, $page, $amount)) {

				// Outputting the Result
				$output['success'] = true;
				$output['output'] = $posts;

			} else {
				$output['error'] = $post->error;
			}

		} else if ($type == 'following_posts') {

			// if successful
			if ($posts = $post->getAllFollowingPosts($uid, $page, $amount)) {

				// Outputting the Result
				$output['success'] = true;
				$output['output'] = $posts;

			} else {
				$output['error'] = $post->error;
			}

		} else if ($type == 'all_posts') {

			// if successful
			if ($posts = $post->getAllPosts($page, $amount)) {
				
				// Outputting the Result
				$output['success'] = true;
				$output['output'] = $posts;

			} else {
				$output['error'] = $post->error;
			}
		} else {
			$output['error'] = 'Type not accepted.';
		}


	// Like Post
	} else if ($action == 'likePost') {

		// Post Class
		$post = new Post($con);

		// Post id
		$pid = $param->post_id;

		// Liking
		if ($l = $post->toggleLikePost($pid)) {
			$output['success'] = true;
			$output['liked'] = $l['liked'];
		} else {
			$output['error'] = $post->error;
		}


	// Follow User
	} else if ($action == 'followUser') {

		// User Class
		$user = new User($con);

		// follow id
		$fid = $param->follow_id;

		// Following
		if ($f = $user->toggleFollowUser($fid)) {
			$output['success'] = true;
			$output['following'] = $f['following'];
		} else {
			$output['error'] = $user->error;
		}

	} else {
		$output['error'] = 'Action not accepted';
	}


	// Cleanup
	$db->close();

}
}

// Setting the Page to Json and spitting the data
header('Content-Type: application/json');
echo json_encode($output);


?>