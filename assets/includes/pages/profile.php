<?php

// Check if User logged in and Verified
if ($data['loggedin']) {
if ($data['verified']) {

	// User Details
	$user = new User($controller->con);
	
	// Getting the logged in users name	
	if ($userinfo = $user->getUserInfo(array('uname', 'fname'))) {
		
		// The Userinfo
		$data['displayname'] = (!empty($userinfo['fname']) ? $userinfo['fname'] : $userinfo['uname']);
	} else {
		echo $user->error;
	}


	// Get user level 2 url
	if (!$profile_user = $controller->getL2Url()) {
		$controller->redirect('profile/'.$userinfo['uname']);
		exit();
	}


	// Getting the user's full name and username
	if ($userinfo = $user->getUserInfoByUsername(array('user_id', 'fname', 'uname'), $profile_user)) {
		$userinfo['displayname'] = (!empty($userinfo['fname']) ? $userinfo['fname'] : $userinfo['uname']);
		$data['title'] = $userinfo['displayname'];
	} else {
		$controller->redirect('404');
	}

	// Gets Users Followers and Following
	if (!$userinfo['followers'] = $user->getUserFollowersAmount($userinfo['user_id'])) {
		echo $user->error;
	}
	
	// Get User Following
	if ($userinfo['following'] = $user->getUserFollowingAmount($userinfo['user_id'])) {
		echo $user->error;
	}

	// Check if user is following
	$data['isFollowing'] = $user->isFollowingUser($userinfo['user_id']);

	// Post
	$post = new Post($controller->con);
	$posts = $post->getUserPosts($userinfo['user_id'], 1, 1);

} else { $controller->redirect('verify');
}} else { $controller->redirect('login'); }



// Rendering the header
$controller->render('dash_header', $data, $controller);
?>


<!-- Profile -->
<div class="jumbotron profile-view">

	<?php
	// Checking if it's the Loggedin user's profile
	if ($data['user_id'] == $userinfo['user_id']) {
	?>
	<div class="profile-settings">
		<a href="<?php echo HOME; ?>settings"><i class="fas fa-cog"></i></a>
	</div>
	<?php
	}
	?>

	<div class="profile-image">
		<img src="<?php echo (file_exists(ASB.'images/profile_pictures/'.$userinfo['uname'].'.png') ? IMG.'profile_pictures/'.$userinfo['uname'].'.png' : IMG.'user.png'); ?>">
	</div>

	<h2 class="profile-name">
		<?php echo $userinfo['displayname']; ?>
	</h2>

	<?php if ($data['user_id'] != $userinfo['user_id']) {

		if ($data['isFollowing']) {
			echo '<button class="btn btn-primary follow-button" data-user-id="'.$userinfo['user_id'].'">Following</button>';
		} else {
			echo '<button class="btn btn-light follow-button" data-user-id="'.$userinfo['user_id'].'">Follow</button>';
		}
	
	} ?>
</div>
<!-- End of Profile -->


<!-- User Stats -->
<div class="card-group user-stats">
	<div class="card">
		<div class="card-body">
			Posts
			<h5 class="card-title"><?php echo $posts['total_posts']; ?></h3>
		</div>
	</div>

	<a href="#" class="card">
		<div class="card-body">
			Followers
			<h5 id="follower_amount" class="card-title"><?php echo $userinfo['followers']; ?></h3>
		</div>
	</a>

	<a href="#" class="card">
		<div class="card-body">
			Following
			<h5 class="card-title"><?php echo $userinfo['following']; ?></h3>
		</div>
	</a>
</div>
<!-- End User Stats -->


<!-- The Posts -->
<div id="postsContainer" data-user-id="<?php echo $userinfo['user_id']; ?>">

	<h2><?php echo $userinfo['displayname']; ?>'s latest Posts</h2>

</div>
<!-- End of the Posts -->

<?php
// load javascript files
$data['loadjs'] = array('profile');

// render the footer
$controller->render('dash_footer', $data);
?>