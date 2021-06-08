var type;
var empty;
var id = 0;

function resetEffects () {
	$(".effects").html("There are no effects<hr />");
	empty = true;
};


$(function () {
	$('[name="type"]').bind("change", function () {
		var newType = $(this).find('[value='+$(this).val()+']').attr("data-item-type");
		if (type != newType) {
			resetEffects();
		} 
		type = newType;
	}).trigger("change");

	var effects = $(".effect-data").html().trim().split(".-.").filter(function (e) {
		return e.length
	}).map(function (e) {
		var e = e.split(".--.");
		return {
			id: e[0], 
			type: e[1]
		};
	});

	$('.new-effect').bind("click", function () {

		if (empty) {
			$(".effects").html("")
		}

		empty = false;
		var html = '<div class="effect-item">';
		html += '	<div class="row">';
		html += '		<div class="col-md-1">';
		html += '			<a class="btn btn-danger btn-block remove">&cross;</a>';
		html += '		</div>';
		html += '		<div class="col-md-2">';
		html += '			<select class="form-control" name="effect['+id+'][id]">';

		effects.filter(function (e) {
			return e.type == type;
		}).map(function (e) {
			html += '<option>' + e.id + '</option>';
		});

		html += '			</select>';
		html += '		</div>';
		html += '		<div class="col-md-2">';
		html += '			<input class="form-control" name="effect['+id+'][value]" placeholder="Value" />';
		html += '		</div>';
		html += '		<div class="col-md-7">';
		html += '			<input class="form-control" name="effect['+id+'][desc]" placeholder="Description ..." />';
		html += '		</div>';
		html += '	</div>';
		html += '	<hr />';
		html += '</div>';

		id++;

		$(".effects").append(html);

		$(".remove").unbind("click").bind("click", function () {
			$(this).parent().parent().parent().remove();
			if (!$(".effect-item").length) resetEffects(); 
		});

	});

	$(".item-effects .item-effect").each(function () {
		newEffect(
			$(this).find(".effect").html().trim(),
			$(this).find(".value").html().trim(),
			$(this).find(".desc").html().trim()
		);
	});

});


function newEffect(effect, value, desc) {
	var tmp = id;
	$('.new-effect').click();
	$('[name="effect['+tmp+'][id]"]').val(effect);
	$('[name="effect['+tmp+'][value]"]').val(value);
	$('[name="effect['+tmp+'][desc]"]').val(desc);
}