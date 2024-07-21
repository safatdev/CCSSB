$(function() {

	// The Post Holder
	var con = $('#postsContainer'),
		cur_page = 1,
		amount = 5,
		profile_user_id = con.data('user-id');

	// Initial Page loading
	loadUserPosts(con, profile_user_id, cur_page, amount);


	// When the user goes to bottom
	$(window).scroll(function() {
		if ($(window).scrollTop() + $(window).height() == $(document).height()) {

			// page increment and reload
			cur_page++;
			loadUserPosts(con, profile_user_id, cur_page, amount);

		}
	});


	$('.follow-button').click(function() {

		var fbutton = $(this);
		followToggle(fbutton.data('user-id'), fbutton);

	});


	// For Following and unfollowing a user
	function followToggle(follow_id, fbutton) {

		// The parameters
		var param = {
			follow_id: follow_id
		};

		// Ajax Post request
		$.post(
			api_url, {
				action: 'followUser',
				param: JSON.stringify(param)
			}, function(ret) {

				// follower amount
				var follower_counter = $('#follower_amount'),
					follower_amount = parseInt(follower_counter.text());

				// checking
				if (ret.success) {
					// check if liked or unliked
					if (ret.following) {
						fbutton.removeClass('btn-light');
						fbutton.addClass('btn-primary');
						fbutton.text('Following');

						follower_counter.text(follower_amount + 1);

					} else {
						fbutton.removeClass('btn-primary');
						fbutton.addClass('btn-light');
						fbutton.text('Follow');

						follower_counter.text(follower_amount - 1);
					}

				} else {
					console.log(ret);
				}

			}
		);

	}

});