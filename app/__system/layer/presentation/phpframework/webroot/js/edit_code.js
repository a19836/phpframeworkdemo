if (typeof _orgAjax == "undefined") { //this avoids the infinit loop if this file gets call twice, like it happens in some modules. DO NOT REMOVE THIS - 2020-09-28!
	var jquery_native_xhr_object = null;
	var _orgAjax = jQuery.ajaxSettings.xhr;
	
	jQuery.ajaxSettings.xhr = function () {
		jquery_native_xhr_object = _orgAjax();
		return jquery_native_xhr_object;
	};
}

function isAjaxReturnedResponseLogin(url) {
	return url && url.indexOf("/__system/auth/login") > 0;
}

function showAjaxLoginPopup(login_url, urls_to_match, success_func) {
	urls_to_match = $.isArray(urls_to_match) ? urls_to_match : [urls_to_match];
	
	var MyFancyPopupLogin = new MyFancyPopupClass();
	var popup = $('#ajax_login_popup');
	var iframe = popup.children("iframe");
	var iframe_on_load_func = function() {
		var current_iframe_url = decodeURI(this.contentWindow.location.href);
		//console.log(current_iframe_url);
		
		if ($.inArray(current_iframe_url, urls_to_match) != -1) {
			MyFancyPopupLogin.hidePopup();
			
			if (typeof success_func == "function")
				success_func();
		}
		else {
			var contents = $(this).contents();
			var w = contents.width();
			var h = contents.height();
			
			w = w > 380 ? w : 380;
			h = h > 320 ? h : 320;
			
			iframe.css({width: w + "px", height: h + "px"});
		}
	};
	
	if (!popup[0]) {
		popup = $('<div class="ajax_login_popup myfancypopup"><iframe></iframe></div>');
		iframe = popup.children("iframe");
		iframe.bind("load", iframe_on_load_func);
		iframe.bind("unload", function() {
			iframe.bind("load", iframe_on_load_func);
		});
		
		//set css here bc there is no css file for this popup.
		popup.css({
			"padding": "0",
			"border-radius": "5px"
		});
		iframe.css({
			"border-radius": "5px"
		});
		
		$("body").append(popup);
	}
	
	iframe[0].src = login_url;
	
	MyFancyPopupLogin.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			MyFancyPopupLogin.getOverlay().off();
			MyFancyPopupLogin.getPopupCloseButton().off().hide();
		},
	});
	
	MyFancyPopupLogin.showPopup();
}
