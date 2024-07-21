<?php
require_once('../../config.php');

header('Content-Type: application/javascript');
echo "var api_url = '".API_URL."',";
echo "login_user_id = ".$_SESSION['uid'] . ";";
?>


// load users posts
function loadUserPosts(spit, uid, page, amount) {

	// The parameters
	var param = {
		type: 'user_posts',
		author_id: uid,
		page: page,
		amount: amount
	};

	// laod posts onto the spit
	loadPosts(spit, param);

}


// load all following
function loadFollowingPosts(spit, page, amount) {

	// The parameters
	var param = {
		type: 'following_posts',
		page: page,
		amount: amount
	};

	// laod posts onto the spit
	loadPosts(spit, param);

}

// load all posts
function loadAllPosts(spit, page, amount) {

	// The parameters
	var param = {
		type: 'all_posts',
		page: page,
		amount: amount
	};

	// laod posts onto the spit
	loadPosts(spit, param);

}

// On document load
function loadPosts(spit, param) {

	// append load message
	if (!(spit.siblings('.postMessage').length)) {
		$('<div class="postMessage"></div>').insertAfter(spit);
	}

	// the action
	function actionButton(post_id) {

		const postid = post_id;

		var ret = `<div class="col-md-6">
						<div class="btn-group float-right">
							<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Actions
							</button>
							
							<div class="dropdown-menu dropdown-menu-right">
								<a class="dropdown-item" href="<?php echo HOME; ?>post/edit/${postid}">Edit</a>
								<a class="dropdown-item" href="<?php echo HOME; ?>post/delete/${postid}">Delete</a>
							</div>
						</div>
		 			</div>`;

		return ret;
	}

	// Ajax Post request
	$.post(
		api_url, {
			action: 'loadPosts',
			param: JSON.stringify(param)
		}, function(ret) {

			// checking
			if (ret.success) {

				// output
				var out = ret.output;
				var posts = out.posts;

				// Checks how many posts are remaining
				if (out.posts_remaining > 0) {

					// looping through
					$.each(posts, function(i, post) {

						// variables
						const post_id = post.post_id;
						const post_content = post.post_content;
						const author_id = post.author_id;
						const author_fname = post.author_fname;
						const author_uname = post.author_uname;
						const author_name = (!author_fname) ? author_uname : author_fname;
						const author_img = post.author_image;
						const post_likes = post.post_likes;
						const user_liked = post.user_liked;
						const like_button = (user_liked) ? 'btn-primary' : 'btn-dark';

						const action_btn = (login_user_id == author_id) ? actionButton(post_id) : '';

						var post_spit = `<div class="card post">
						 	<img class="post-author-image" src="${author_img}">

							<div class="card-header">
								<a class="post-author" href="<?php echo HOME; ?>profile/${author_uname}">${author_name}</a>
							</div>

						 	<div class="card-body">
						 		<p class="card-text">${post_content}</p>
						 	</div>

						 	<div class="card-footer">
						 		<div class="row">
							 		<div class="col-md-6">
								 		<ul class="list-group list-group-horizontal">
											<li class="list-group-item"><button onclick="likeToggle(${post_id}, this);" class="btn ${like_button}"><i class="fas fa-thumbs-up"></i> <span class="like-amount">${post_likes}</span></button></li>
											<li class="list-group-item"><button class="btn btn-dark"><i class="fas fa-comments"></i></button></li>
											<li class="list-group-item"><button class="btn btn-dark"><i class="fas fa-share"></i></button></li>
										</ul>
						 			</div>
						 	
						 			${action_btn}

						 		</div>
						 	</div>
					 	</div>`;

					 	// Spitting to the Post
						spit.append(post_spit);

					});

				} else {
					spit.siblings('.postMessage').text('Nothing to show');
				}


			} else {
				spit.text(out.error);
			}

		}
	);

}




// like or unlike a post
function likeToggle(post_id, like_button) {

	// like_button
	var like_button = $(like_button);

	// The parameters
	var param = {
		post_id: post_id
	};

	// Ajax Post request
	$.post(
		api_url, {
			action: 'likePost',
			param: JSON.stringify(param)
		}, function(ret) {

			// checking
			if (ret.success) {

				// like amount
				var like_amount = like_button.find('.like-amount'),
					amount = parseInt(like_amount.text());

				// check if liked or unliked
				if (ret.liked) {
					
					like_button.removeClass('btn-dark');
					like_button.addClass('btn-primary');
					like_amount.text(amount + 1);
					
				} else {
					like_button.removeClass('btn-primary');
					like_button.addClass('btn-dark');
					like_amount.text(amount - 1);
				}

			} else {
				console.log(ret);
			}

		}
	);
}

