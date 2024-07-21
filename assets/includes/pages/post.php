<?php

// Passing of the Data
$data['title'] = 'Post';

// Check if User logged in and Verified
if ($data['loggedin']) {
if ($data['verified']) {

	// User Details
	$user = new User($controller->con);

	// Getting the logged in users name	
	if ($userinfo = $user->getUserInfo(array('uname', 'fname'))) {
		$data['displayname'] = (!empty($userinfo['fname']) ? $userinfo['fname'] : $userinfo['uname']);
	} else {
		echo $user->error;
	}

	// Requiring Level 2 and 3
	if (!$action = $controller->getL2Url() OR !$param = $controller->getL3Url()) {
		$controller->redirect('404');	
	}

	// post
	$post = new Post($controller->con);

	// check if post exists
	if ($postinfo = $post->getPost($param)) {
		$postinfo['author_name'] = (!empty($postinfo['fname']) ? $postinfo['fname'] : $postinfo['uname']);
	} else {
		$controller->redirect('404');
	}

	// only these actions are accepted
	if ($action == 'view') {

	// Create post
	} else if ($action == 'create') {

		// check if submit
		if (isset($_POST['create_submit'])) {

			// getting the post content
			$post_content = $_POST['post_content'];

			// Creating the post
			if ($pid = $post->createPost($post_content)) {

				// redirect to viewing
				$controller->redirect('post/view/'.$pid);

			} else {
				$error = $post->error;
			}

		}

	// Edit Post
	} else if ($action == 'edit') {

		// permission
		if ($data['user_id'] == $postinfo['author_id']) {
			$data['permission'] = true;
		} else {
			$data['permission'] = false;
			$error = 'You do not have permission to edit this post';
		}

		// updating
		if (isset($_POST['edit_submit'])) {

			// variables
			$post_content = $_POST['post_content'];

			// exec
			if ($post->updatePost($postinfo['post_id'], $post_content)) {

				// redirecting to view
				$controller->redirect('post/view/'.$postinfo['post_id']);

			} else {
				$error = $post->error;
			}

		}

	// Delete Post
	} else if ($action == 'delete') {

		// permission
		if ($data['user_id'] == $postinfo['author_id']) {
			$data['permission'] = true;
		} else {
			$data['permission'] = false;
			$error = 'You do not have permission to delete this post';
		}

		// checking if isset
		if (isset($_POST['delete_submit'])) {

			// Check if deleted
			if ($post->deletePost($param)) {
				$success = 'Your Poast was deleted';
			} else {
				$error = $post->error;
			}

		}


	// If not accepted
	} else {
		$controller->redirect('404');
	}


} else { $controller->redirect('verify');
}} else { $controller->redirect('login'); }



// Rendering the header
$controller->render('dash_header', $data, $controller);

// errors
if (isset($error)) {
	echo '<div class="alert alert-danger">'.$error.'</div>';
} else if (isset($success)) {
	echo '<div class="alert alert-success">'.$success.'</div>';
	echo '<br><a href="'.HOME.'timeline" class="btn btn-primary">Go back to Timeline</a>';
}

// view
if ($action == 'view') {
?>

<div id="postsContainer">
	<div class="card post">
	 	<img class="post-author-image" src="<?php echo $postinfo['author_image']; ?>">

		<div class="card-header">
			<a class="post-author" href="<?php echo HOME.'profile'.$postinfo['author_uname']; ?>"><?php echo $data['displayname']; ?></a>
		</div>

	 	<div class="card-body">
	 		<p class="card-text"><?php echo $postinfo['post_content']; ?></p>
	 	</div>

	 	<div class="card-footer">
			<ul class="list-group list-group-horizontal">
				<li class="list-group-item"><button data-post-id="<?php echo $postinfo['post_id']; ?>" class="likeToggleButton btn <?php echo ($postinfo['user_liked'] ? 'btn-primary' : 'btn-dark'); ?>"><i class="fas fa-thumbs-up"></i> <span class="like-amount"><?php echo $postinfo['post_likes']; ?></span></button></li>
				<li class="list-group-item"><button class="btn btn-dark"><i class="fas fa-comments"></i></button></li>
				<li class="list-group-item"><button class="btn btn-dark"><i class="fas fa-share"></i></button></li>
			</ul>
	 	</div>
	</div>
</div>

<?php
if ($data['user_id'] == $postinfo['user_id']) {
?>
<ul style="margin-top:30px;" class="list-group list-group-horizontal float-right">
	<li class="list-group-item list-group-item-warning">
		<a href="<?php echo HOME.'post/edit/'.$postinfo['post_id']; ?>">Edit</a>
	</li>

	<li class="list-group-item list-group-item-danger">
		<a href="<?php echo HOME.'post/delete/'.$postinfo['post_id']; ?>">Delete</a>
	</li>
</ul>
<?php } ?>


<?php
} else if ($action == 'create' OR $action == 'edit') {

// Create
$c = ($action == 'create') ? true : false;

// Permission
if (($action == 'edit' && $data['permission']) OR $c) {
	?>

	<form class="timeline-post-create" method="post" action="<?php echo HOME.'post/'.$action.'/'.(($c) ? '1' : $postinfo['post_id']); ?>">

		<span class="form-element">
			<textarea class="form-control" name="post_content" placeholder="Create a quick Post"><?php echo ($action == 'edit') ? $postinfo['post_content'] : ''; ?></textarea>
		</span>

		<?php if ($action == 'create') { ?>
		<button class="btn btn-dark float-right" type="submit" name="create_submit">Post</button>
		<?php } else if ($action == 'edit') { ?>
		<button class="btn btn-dark float-right" type="submit" name="edit_submit">Save</button>
		<?php } ?>
	</form>

	<?php
	}
} else if ($action == 'delete') {

if ($data['permission']) {
if (!isset($success)) {
?>

<br>
<br>
<br>
<form method="post" action="<?php echo HOME.'post/delete/'.$postinfo['post_id']; ?>">
	
	<h2>Are you sure you want to delete this Post?</h2>
	<p>Once deleted, it cannot be recovered!</p><br>

	<button class="btn btn-danger" type="submit" name="delete_submit">Delete!</button>

</form>

<?php
}
}
}
?>

<?php

// to load the post js
$data['loadjs'] = 'post';

// the footer
$controller->render('dash_footer', $data);
?>