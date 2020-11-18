$(function () {
    $(".navbar-toggle").unbind().bind("click", function () {
        var body = $("body");
        if (body.hasClass("show-sidebar")) {
            body.removeClass("show-sidebar");
        } else {
            body.addClass("show-sidebar");
        }
    });
});