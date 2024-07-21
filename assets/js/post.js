$(function() {

	var likeButton = $('.likeToggleButton');

	likeButton.click(function() {

		likeToggle($(this).data('post-id'), this);

	});

});