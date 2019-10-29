var timeOffset = 0;

$(function () {
    timeOffset = Math.floor(new Date() / 1000) - parseInt($("meta[name='timestamp']").attr("content"));
});

function checkTimer(interval) {

    $('[data-timer-type="name"], [data-timer-type="inline"]').each(function () {
        var ts = parseInt($(this).attr("data-timer"));
        var now = Math.round(new Date() / 1000) - timeOffset;
        var time = ts - now;
        
        if (time > 0) {
            if ((time - interval) == -1) {
                var removeClass = 'timer-active';
                var addClass = 'timer-done';
            } else {
                var removeClass = 'timer-done';
                var addClass = 'timer-active';
            }
        } else {
            var removeClass = 'timer-active';
            var addClass = 'timer-done';
        }

        if(addClass == 'timer-done' && $(this).attr('data-reload-when-done') != undefined) {
            setTimeout(function () {
                document.location.reload();
            }, 2500);
        }

        if(addClass == 'timer-done' && $(this).attr('data-remove-when-done') != undefined) {
            $(this).parent().remove();
        }
            
        var hours = Math.floor(time/3600);
        var mins = Math.floor((time - (hours * 3600))/60);
        var sec = time % 60;

        if (hours < 10) hours = '0' + hours;
        if (mins < 10) mins = '0' + mins;
        if (sec < 10) sec = '0' + sec;

        if (time < 0) {
            var hours = "00";
            var mins = "00";
            var sec = "00";
        }

        if ($(this).attr('data-timer-type') == 'name') {
            $(this).removeClass(removeClass).addClass(addClass).find('span').eq(1).html(hours+":"+mins+":"+sec);
        } else {
            $(this).removeClass(removeClass).addClass(addClass).html(hours+":"+mins+":"+sec);
        }
        
    });
}

$(function () {
    
    $(".alert .close").bind("click", function () {
        $(this).parent().remove();
    });

    var d = new Date();

    checkTimer(0);

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