$(function() {

	// A small script for gradient change in the home page

	var fe = $("span.form-element"), fet = fe.find(".form-control");
	fet.focus(function() {
		$(this).closest(fe).addClass("active");
		$(this).focusout(function() {
			$(this).closest(fe).removeClass("active");			
		});
	});
});