var iframe_overlay = null; //Tobe used by sub-pages

$(function(){
	var iframe = $('#content iframe');
	iframe_overlay = $('#content .iframe_overlay');
	
	$(window).resize(function() {
		resizeIframe(iframe);
	});
	
	resizeIframe(iframe);
	
	var win_url = "" + document.location;
	win_url = win_url.indexOf("#") > 0 ? win_url.substr(0, win_url.indexOf("#")) : win_url;
	
	var iframe_unload_func = function (e) {
		iframe_overlay.show();
	};
	
	iframe.load(function(){
		$(iframe[0].contentWindow).unload(iframe_unload_func);
	
		iframe_overlay.hide();
		
		//prepare redirect when user is logged out
		this.contentWindow.$.ajaxSetup({
			complete: function(jqXHR, textStatus) {
				if (jqXHR.status == 200 && jqXHR.responseText.indexOf('<div class="login">') > 0 && jqXHR.responseText.indexOf('<div id="layoutAuthentication">') > 0) 
					document.location = win_url;
		    	}
		});
	});
	$(iframe[0].contentWindow).unload(iframe_unload_func);
	
	//prepare redirect when user is logged out
	$.ajaxSetup({
		complete: function(jqXHR, textStatus) {
			if (jqXHR.status == 200 && jqXHR.responseText.indexOf('<div class="login">') > 0 && jqXHR.responseText.indexOf('<div id="layoutAuthentication">') > 0)
				document.location = win_url;
	    	}
	});
});

function resizeIframe(iframe) {
	var h = $(window).height() - $('#main_menu').height();
	iframe.css("height", h + "px");	
}

function goToHandler(url, a, attr_name, originalEvent) {
	$("#content .iframe_overlay").show();
	
	try {
		$("#content iframe")[0].src = url;
	}
	catch(e) {
		//sometimes gives an error bc of the iframe beforeunload event. This doesn't matter, but we should catch it and ignore it.
		if (console && console.log)
			console.log(e);
	}
}

function goBack() {
	var iframe = $("#content iframe")[0];
	var win = iframe.contentWindow;
	
	if (win)
		win.history.go(-1);
}

function refreshIframe() {
	$("#content .iframe_overlay").show();
	
	var iframe = $("#content iframe")[0];
	var doc = (iframe.contentWindow || iframe.contentDocument);
	doc = doc.document ? doc.document : doc;
	
	try {
		iframe.src = doc.location;
	}
	catch(e) {
		//sometimes gives an error bc of the iframe beforeunload event. This doesn't matter, but we should catch it and ignore it.
		if (console && console.log)
			console.log(e);
	}
}
