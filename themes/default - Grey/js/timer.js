
	function checkTimer(intval) {

		$('[data-timer-type="name"], [data-timer-type="inline"]').each(function () {
			
			var time = parseInt($(this).attr("data-timer"));
			
			if (time > 0) {
				$(this).attr('data-timer', (time-intval));
				
				if ((time-intval) == 0) {
					$(this).removeClass('timer-active').addClass('timer-done');
				} else {
					$(this).removeClass('timer-done').addClass('timer-active');
				}
			} else {
				$(this).removeClass('timer-active').addClass('timer-done');
			}
				
			var hours = Math.floor((time/3600));

			time -= (hours * 3600);

			var mins = Math.floor((time/60));

			time -= (mins * 60);

			var sec = time;

			hours += '';
			mins += '';
			sec += '';

			if (hours.length == 1) {hours = '0'+hours;}
			if (mins.length == 1) {mins = '0'+mins;}
			if (sec.length == 1) {sec = '0'+sec;}

			if ($(this).attr('data-timer-type') == 'name') {
				
				$(this).find('span').eq(1).html(hours+":"+mins+":"+sec);
			
			} else {
			
				$(this).html(hours+":"+mins+":"+sec);
			
			}
			
		});
	}

	$(function () {
		
		checkTimer(1);

		var timer = setInterval(function () {

			checkTimer(1);

		}, 1000);

		$('[data-timer-type="name"]').bind("mouseover", function () {

			$(this).find('span').eq(0).hide();
			$(this).find('span').eq(1).show();

		});

		$('[data-timer-type="name"]').bind("mouseout", function () {

			$(this).find('span').eq(1).hide();
			$(this).find('span').eq(0).show();

		});

		$('[data-timer-type="name"]').each(function () {
			$(this).find('span').eq(1).hide();
		});
	});