$.ajaxSetup({
    headers: { 
		'return-json': true
    }
});

function ajaxRequest(method, url, data, options) {

	var defaultOpts = {
		method: method || {}, 
		url: url || {}, 
		data: data || {}
	};

	var opts = $.extend(defaultOpts, (options || {}))

	history.pushState(opts, "", url);


	return $.ajax(opts);

}

function handleResponse (data) {
	for (var obj in data) {
		var value = data[obj];
		$("[data-ajax-element='"+obj+"']").each(function () {
			var el = $(this);
			var elData = el.data();
			switch (elData.ajaxType) {
				case "html":
					el.html(value);
					bindEvents(el);
				break;
				case "attr":
					el.attr(data.ajaxAttr, value);
				break;
				default:
					el.text(value)
			}
		});
	}


	if (data.moduleCSSFile) {
		if (!$("head [rel='stylesheet'][href='"+data.moduleCSSFile+"']").length) $('head').append('<link rel="stylesheet" href="' + data.moduleCSSFile + '" type="text/css" />');
	}
	if (data.moduleJSFile) {
		if (!$("body script[src='"+data.moduleJSFile+"']").length) $('body').append('<script src="' + data.moduleJSFile + '"></script>');
	}
}

function bindEvents(el) {
	el.find("a:not([data-not-ajax])").bind("click", function (e) {
		e.preventDefault();
		var data = $(this).data();
		var url = $(this).attr("href");

		ajaxRequest("GET", url).done(function (data) {
			handleResponse(data);
		});

	});
}

bindEvents($("body"));