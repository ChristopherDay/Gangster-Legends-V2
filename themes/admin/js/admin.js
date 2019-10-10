$(function () {
	$("select[data-value]").each(function () {
		if ($(this).attr("data-value")) $(this).val($(this).attr("data-value"));
	});
});