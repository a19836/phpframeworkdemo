var auto_convert = false;
var auto_save = false;
var last_auto_save_time = null;
var auto_save_action_interval = 5 * 1000; //5 seconds in milliseconds
var auto_save_connection_ttl = 30 * 1000; //30 seconds in milliseconds
var is_from_auto_save = false;
var saved_obj_id = null;
var MyFancyPopupLogin = new MyFancyPopupClass();

if (typeof _orgAjax == "undefined") { //this avoids the infinit loop if this file gets call twice, like it happens in some modules. DO NOT REMOVE THIS - 2020-09-28!
	var jquery_native_xhr_object = null;
	var _orgAjax = jQuery.ajaxSettings.xhr;
	
	jQuery.ajaxSettings.xhr = function () {
		jquery_native_xhr_object = _orgAjax();
		return jquery_native_xhr_object;
	};
}

/* Auto Save Functions */
function addAutoSaveMenu(selector, callback) {
	var elm = $(selector);
	var p = elm.parent();
	var item = p.children(".auto_save_activation");
	
	if (!item[0]) {
		if (!callback)
			callback = "onToggleAutoSave";
		
		item = $('<li class="auto_save_activation" title="Is Auto Save Active">'
					+ '<i class="icon auto_save_activation"></i>'
					+ 'Auto Save On: '
					+ '<input type="checkbox" onChange="toggleAutoSaveCheckbox(this, ' + callback + ')" value="1">'
				+ '</li>');
		
		elm.before(item);
	}
	
	return item;
}
function initAutoSave(selector) {
	setInterval(function() {
		if (auto_save) {
			var time = last_auto_save_time + auto_save_connection_ttl + auto_save_action_interval; //Note that the timeout for the auto save ajax requests is auto_save_connection_ttl, so we add the auto_save_action_interval to the auto_save_connection_ttl so we can have a bigger margin.
			
			if (!last_auto_save_time || time < (new Date()).getTime()) { //if last_auto_save_time is null (or was reseted) or if took more than 1 minute
				last_auto_save_time = (new Date()).getTime();
				is_from_auto_save = true;
				
				//execute save function
				$(selector).trigger("click");
			}
		}
	}, auto_save_action_interval);
}
function resetAutoSave() {
	last_auto_save_time = null;
}
function toggleAutoSaveCheckbox(elm, callback) {
	setTimeout(function() {
		if ($(elm).is(":checked")) 
			auto_save = true;
		else
			auto_save = false;
		
		if (typeof callback == "function")
			callback();
	}, 10);
}
function enableAutoSave(callback) {
	auto_save = true;
	
	if (typeof callback == "function")
		callback();
}
function disableAutoSave(callback) {
	auto_save = false;
	
	if (typeof callback == "function")
		callback();
}
function onToggleAutoSave() {
	var inputs = $(".top_bar li.auto_save_activation input");
	
	if (auto_save) {
		inputs.attr("checked", "checked").prop("checked", true);
	}
	else {
		inputs.removeAttr("checked").prop("checked", false);
	}
}
function onToggleWorkflowAutoSave() {
	var inputs = $(".taskflowchart .workflow_menu ul.dropdown li.auto_save_activation input");
	
	if (auto_save) {
		jsPlumbWorkFlow.jsPlumbTaskFile.auto_save = false; //should be false bc the saveObj calls the getCodeForSaving method which already saves the workflow by default, and we don't need 2 saves at the same time.
		jsPlumbWorkFlow.jsPlumbProperty.auto_save = true;
		$(".taskflowchart").removeClass("auto_save_disabled");
		
		inputs.attr("checked", "checked").prop("checked", true);
	}
	else {
		jsPlumbWorkFlow.jsPlumbTaskFile.auto_save = false;
		jsPlumbWorkFlow.jsPlumbProperty.auto_save = false;
		$(".taskflowchart").addClass("auto_save_disabled");
		
		inputs.removeAttr("checked").prop("checked", false);
	}
}
function prepareAutoSaveVars() {
	var e = window.event;
	
	//this means that there was a real event clicked and that was an user action. So we reset the is_from_auto_save var, so it doesn't show the confirmation box when it tries to convert the workflow to code and hide the successfull saving message.
	if (e && (
		(e.screenX && e.screenX != 0 && e.screenY && e.screenY != 0) 
		|| e.shiftKey //shift is down
		|| e.altKey //alt is down
		|| e.ctrlKey //ctrl is down
		|| e.metaKey //cmd is down
	)) 
		is_from_auto_save = false;
}

/* Auto convert Functions */
function addAutoConvertMenu(selector, callback) {
	var elm = $(selector);
	var p = elm.parent();
	var item = p.children(".auto_convert_activation");
	
	if (!item[0]) {
		if (!callback)
			callback = "onToggleAutoConvert";
		
		item = $('<li class="auto_convert_activation" title="Is Auto Convert Active">'
					+ '<i class="icon auto_convert_activation"></i>'
					+ 'Auto Convert On: '
					+ '<input type="checkbox" onchange="toggleAutoConvertCheckbox(this, ' + callback + ')" value="1">'
				+ '</li>');
		
		elm.before(item);
	}
	
	return item;
}
function toggleAutoConvertCheckbox(elm, callback) {
	elm = $(elm);
	
	setTimeout(function() {
		if (elm.is(":checked")) 
			auto_convert = true;
		else
			auto_convert = false;
		
		if (typeof callback == "function")
			callback();
	}, 10);
}
function enableAutoConvert(callback) {
	auto_convert = true;
	
	if (typeof callback == "function")
		callback();
}
function disableAutoConvert(callback) {
	auto_convert = false;
	
	if (typeof callback == "function")
		callback();
}
function onToggleAutoConvert() {
	var inputs = $(".top_bar li.auto_convert_activation input");
	
	if (auto_convert) {
		inputs.attr("checked", "checked").prop("checked", true);
	}
	else {
		inputs.removeAttr("checked").prop("checked", false);
	}
}
function onToggleWorkflowAutoConvert() {
	var inputs = $(".taskflowchart .workflow_menu ul.dropdown li.auto_convert_activation input");
	
	if (auto_convert) {
		inputs.attr("checked", "checked").prop("checked", true);
	}
	else {
		inputs.removeAttr("checked").prop("checked", false);
	}
}

/* AJAX Functions */
function isAjaxReturnedResponseLogin(url) {
	return url && url.indexOf("/__system/auth/login") > 0;
}

function showAjaxLoginPopup(login_url, urls_to_match, success_func) {
	urls_to_match = $.isArray(urls_to_match) ? urls_to_match : [urls_to_match];
	var auto_save_bkp = auto_save;
	
	var popup = $('.ajax_login_popup');
	var iframe = popup.children("iframe");
	var iframe_on_load_func = function() {
		var current_iframe_url = decodeURI(this.contentWindow.location.href);
		//console.log(current_iframe_url);
		//console.log(urls_to_match);
		
		if ($.inArray(current_iframe_url, urls_to_match) != -1) {
			MyFancyPopupLogin.hidePopup();
			auto_save = auto_save_bkp;
			
			if (typeof success_func == "function")
				success_func();
		}
		else {
			/*var contents = $(this).contents();
			var w = contents.width();
			var h = contents.height();
			
			w = w > 380 ? w : 380;
			h = h > 280 ? h : 280;
			
			iframe.css({width: w + "px", height: h + "px"});*/
			iframe.css({width: "380px", height: "280px"});
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
	
	iframe[0].src = login_url + (login_url.indexOf("?") > -1 ? "&" : "?") + "popup=1";
	
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

/* FullScreen Functions */

function isInFullScreen() {
	return !window.screenTop && !window.screenY;
}

function toggleFullScreen(elm) {
	elm = $(elm);
	
	var in_full_screen = isInFullScreen();
	
	if (in_full_screen) {
		closeFullscreen();
		elm.removeClass("active");
	}
	else {
		openFullscreen();
		elm.addClass("active");
	}
	
	if (typeof onToggleFullScreen == "function")
		onToggleFullScreen(!in_full_screen);
}

function openFullscreen(elm) {
	if (!elm)
		elm = $("body")[0];
	
	if (elm.requestFullscreen)
		elm.requestFullscreen();
	else if (elm.webkitRequestFullscreen) /* Safari */
		elm.webkitRequestFullscreen();
	else if (elm.msRequestFullscreen) /* IE11 */
		elm.msRequestFullscreen();
}

function closeFullscreen() {
	try {
		if (document.exitFullscreen)
			document.exitFullscreen();
		else if (document.webkitExitFullscreen) /* Safari */
			document.webkitExitFullscreen();
		else if (document.msExitFullscreen) /* IE11 */
			document.msExitFullscreen();
	}
	catch(e) {
		//if no fullscreen and this function gets called, it will give an exception
	}
}
