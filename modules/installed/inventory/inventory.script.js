$(".inventory-item .show-options").bind("click", function () {
	var elm = $(this).parent().parent().find(".inventory-actions");
	if (elm.hasClass("open")) {
		elm.removeClass("open");
	} else {
		$(".inventory-actions.open").removeClass("open");
		elm.addClass("open");
	}
});