$(function () {
	$(".navbar-toggle, .close-mobile-menu").unbind().bind("click", function () {
		var e = $(".mobile-menu");
		if (e.is(":visible")) {
			e.hide();
		} else {
			e.show();
		}
	});
});