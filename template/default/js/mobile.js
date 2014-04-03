
	$(function () {
		
		$('.navigation .panel-heading').bind('click', function () {
			
			var body = $(this).parent().find('.panel-body');
			
			if (body.hasClass('hidden-xs')) {
				body.removeClass('hidden-xs');
			} else {
				body.addClass('hidden-xs');
			} 
			
		});
		
	});