<?php

// Passing of the Data
$data['title'] = 'Timeline';

// Check if User logged in and Verified
if ($data['loggedin']) {
if ($data['verified']) {

	// User Details
	$user = new User($controller->con);

	// Getting the user's full name and username
	if ($userinfo = $user->getUserInfo(array('fname', 'uname'), $data['user_id'])) {
		$data['displayname'] = (!empty($userinfo['fname']) ? $userinfo['fname'] : $userinfo['uname']);
	} else {
		echo $user->error;
	}

} else { $controller->redirect('verify');
}} else { $controller->redirect('login'); }

// Rendering the header
$controller->render('dash_header', $data, $controller);
?>


<form class="timeline-post-create" method="post" action="<?php echo HOME; ?>post/create/1">

	<span class="form-element">
		<textarea class="form-control" name="post_content" placeholder="Write a Quick Post"></textarea>
	</span>

	<button class="btn btn-dark float-right" type="submit" name="create_submit">Post</button>
</form>


<!-- The Timeline Tab -->
<ul class="nav nav-tabs" id="home-tab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#timeline-panel" role="tab" aria-controls="timeline-panel" aria-selected="true">Timeline</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#recent-panel" role="tab" aria-controls="recent-panel" aria-selected="false">All Posts</a>
	</li>
</ul>


<div class="tab-content" id="home-panels">
	<div class="tab-pane fade show active" id="timeline-panel" role="tabpanel" aria-labelledby="home-tab">
		<div id="postsContainer">
		</div>
	</div>
	<div class="tab-pane fade" id="recent-panel" role="tabpanel" aria-labelledby="profile-tab">
	</div>
</div>
<!-- End of Timeline tab -->

<?php
// to load the posts
$data['loadjs'] = array('timeline');

// Displaying the Dashboard Footer
$controller->render('dash_footer', $data);
?>