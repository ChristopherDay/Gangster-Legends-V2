$(function () {
    $(".navbar-toggle, .close-mobile-menu").unbind().bind("click", function () {
        var e = $(".mobile-menu");
        if (e.is(":visible")) {
            e.hide();
            $("body").removeClass("no-scroll");
        } else {
            e.show();
            $("body").addClass("no-scroll");
        }
    });
});