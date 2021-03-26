$(function () {
    $(".navbar-toggle").unbind().bind("click", function () {
        var body = $("body");
        if (body.hasClass("show-sidebar")) {
            body.removeClass("show-sidebar");
        } else {
            body.addClass("show-sidebar");
        }
    });

    $(document).on("gl-ajax-page-load", function () {
        var body = $("body");
        body.removeClass("show-sidebar");
    });

});