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

function recursiveObject(key, data) {
	for (var obj in data) {
		var value = data[obj];

		if (typeof value == "object") {
			var newKey = key + obj + ".";
			recursiveObject(newKey, value);
		} else {
			$("[data-ajax-element='"+ key + obj +"']").each(function () {
				var el = $(this);
				var elData = el.data();
				var newValue = value;

				if (elData.ajaxValuePrefix) newValue = elData.ajaxValuePrefix + newValue;
				if (elData.ajaxValuePostfix) newValue = newValue + elData.ajaxValuePostfix;

				if (obj == "health") {
					console.log(newValue);
				}

				switch (elData.ajaxType) {
					case "css":
						el.css(elData.ajaxCss, newValue);
					break
					case "html":
						el.html(newValue);
						bindEvents(el);
					break;
					case "attr":
						el.attr(elData.ajaxAttr, newValue);
					break;
					default:
						el.text(newValue)
				}
			});
		}
	}
}

function handleResponse (data) {

	recursiveObject("", data);

	if (data.moduleCSSFile) {
		if (!$("head [rel='stylesheet'][href='"+data.moduleCSSFile+"']").length) $('head').append('<link rel="stylesheet" href="' + data.moduleCSSFile + '" type="text/css" />');
	}
	if (data.moduleJSFile) {
		if (!$("body script[src='"+data.moduleJSFile+"']").length) $('body').append('<script src="' + data.moduleJSFile + '"></script>');
	}

	$(document).trigger("gl-ajax-page-load");
}

function bindEvents(el) {
	el.find('form:not([data-not-ajax])').submit(function() {
	  	var form = $(this);
		var url = $(this).attr("action");
	  	var method = form.attr("method").toLowerCase();;
	  	var data = form.serializeArray();

	  	var btn = form.find(".btn:focus" );

	  	if (btn.attr("name") && btn.attr("value")) {
	  		data.push({
	  			name:btn.attr("name"), 
	  			value:btn.attr("value")
	  		});
	  	}


	  	ajaxRequest(method, url, data).done(function (data) {
			handleResponse(data);
		});

		return false;
	});

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