$(function() {

	// The Post Holder
	var con = $('#postsContainer'),
		tline = true,
		cur_page = 1,
		amount = 5;

	// Initial Page loading
	loadFollowingPosts(con, cur_page, amount);

	// The Tabs
	var tpanel = '#timeline-panel',
		rpanel = '#recent-panel';

	// tab change
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			
		// check tab
		targetp = $(e.target).attr('href');

		// Check if target is recent
		if (targetp == rpanel) {
			
			// cleanup
			ppanel = $(rpanel);
			ppanel.html('');
			con.html('');
			ppanel.append(con);
			tline = false;
			cur_page = 1;

			// get all recent posts
			loadAllPosts(con, cur_page, amount);

		} else if (targetp == tpanel) {
			
			// cleanup
			ppanel = $(tpanel);
			ppanel.html('');
			con.html('');
			ppanel.append(con);
			tline = true;
			cur_page = 1;

			// get all following posts
			loadFollowingPosts(con, cur_page, amount);

		}

	});


	// When the user goes to bottom
	$(window).scroll(function() {
		if ($(window).scrollTop() + $(window).height() == $(document).height()) {
			
			// incrementing the page
			cur_page++;

			// if it's timeline
			if (tline) {
				loadFollowingPosts(con, cur_page, amount);
			} else {
				loadAllPosts(con, cur_page, amount);
			}

		}
	});

});