var iframe_overlay = null; //To be used by sub-pages

$(function() {
	//change label "Vendors" to "Biblioteca Externa"
	$("#file_tree li.main_node_vendor > a > label").html("Biblioteca Externa");
	
	//prepare menu panel
	/*var menu_panel = $('#menu_panel');
	
	menu_panel.on("mouseover", function(){
		if (menu_panel.attr("is_open") != "1") {
			menu_panel.attr("is_open", "1");
			menu_panel.css("height", "30px");
		}
	});
	menu_panel.on("mouseout", function(){
		if (menu_panel.attr("is_open") == "1") {
			menu_panel.css("height", "5px");
			menu_panel.attr("is_open", "0");
		}
	});*/
	
	//prepare right panel iframe
	var win_url = "" + document.location;
	win_url = win_url.indexOf("#") != -1 ? win_url.substr(0, win_url.indexOf("#")) : win_url;
	
	iframe_overlay = $('#right_panel .iframe_overlay');
	var iframe = $('#right_panel iframe');
	var iframe_unload_func = function (e) {
		iframe_overlay.show();
	};
	
	iframe.load(function() {
		$(iframe[0].contentWindow).unload(iframe_unload_func);
	
		iframe_overlay.hide();
		
		//prepare redirect when user is logged out
		try {
			iframe[0].contentWindow.$.ajaxSetup({
				complete: function(jqXHR, textStatus) {
					if (jqXHR.status == 200 && jqXHR.responseText.indexOf('<div class="login">') > 0 && jqXHR.responseText.indexOf('<h1>Login</h1>') > 0) 
						document.location = win_url;
			    	}
			});
		}
		catch (e) {}
	});
	$(iframe[0].contentWindow).unload(iframe_unload_func);
	
	//prepare redirect when user is logged out
	$.ajaxSetup({
		complete: function(jqXHR, textStatus) {
			if (jqXHR.status == 200 && jqXHR.responseText.indexOf('<div class="login">') > 0 && jqXHR.responseText.indexOf('<h1>Login</h1>') > 0)
				document.location = win_url;
	    	}
	});
	
	//prepare hide_panel
	$("#hide_panel").draggable({
		axis: "x",
		appendTo: 'body',
		containment: $("#hide_panel").parent(),
		cursor: 'move',
		cancel: '.button',
		start : function(event, ui) {
			if ($(this).children(".button").hasClass("minimize")) {
				$('#right_panel iframe').hide(); // We need to hide the iframe bc the draggable event has some problems with iframes
				return true;
			}
			return false;
		},
		drag : function(event, ui) {
			updatePanelsAccordingWithHidePanel();
		},
		stop : function(event, ui) {
			updatePanelsAccordingWithHidePanel();
			$('#right_panel iframe').show();
		}
	});
	
	//prepare menu tree
	initFileTreeMenu();
	
	//change tree to be separated with tabs
	if ( $("#left_panel").is(".left_panel_with_tabs") ) {
		var file_tree_ul = $("#file_tree > ul");
		var lis = file_tree_ul.children("li");
		var tabs_html = '<ul class="tabs">';
		
		$.each(lis, function(idx, li) {
			li = $(li);
			var id = li.attr("id");
			var label = li.find(" > a > label").text();
			var djst = li.attr("data-jstree");
			var m = djst.match(/"icon"\s*:\s*"(.*)"/);
			var classes = m[1];
			var tab_classes = "tab_main_node tab_" + classes.split(" ").join(" tab_");
			
			tabs_html	+= '<li class="' + tab_classes + '"><a href="#' + id + '" title="' + label + '"><i class="tab_icon ' + classes + '"></i></a></li>';
			
			li.addClass("main_tree_node");
		});
		
		tabs_html	+= '</ul>';
		
		file_tree_ul.prepend(tabs_html);
		file_tree_ul.tabs();
		
		file_tree_ul.find(" > ul.tabs > .tab_main_node a").click(function(idx, a){
			$(".jqcontextmenu").hide();
		});
	}
});


function expandLeftPanel(elm) {	
	var hide_panel = $("#hide_panel");
	hide_panel.css("left", (parseInt(hide_panel.css("left")) + 50) + "px");
	
	updatePanelsAccordingWithHidePanel();
}

function collapseLeftPanel(elm) {
	var hide_panel = $("#hide_panel");
	hide_panel.css("left", (parseInt(hide_panel.css("left")) - 50) + "px");
	
	updatePanelsAccordingWithHidePanel();
}

function updatePanelsAccordingWithHidePanel() {
	var menu_panel = $("#menu_panel");
	var left_panel = $("#left_panel");
	var hide_panel = $("#hide_panel");
	var right_panel = $("#right_panel");
	
	var left = parseInt(hide_panel.css("left"));
	left = left < 50 ? 50 : (left > $(window).width() - 50 ? $(window).width() - 50 : left);
	
	menu_panel.css("width", left + "px");
	left_panel.css("width", left + "px");
	hide_panel.css("left", left + "px");
	right_panel.css("left", (left + 5) + "px"); //5 is the width of the hide_panel
}

function toggleLeftPanel(elm) {
	button = $(elm);
	
	var menu_panel = $("#menu_panel");
	var left_panel = $("#left_panel");
	var hide_panel = $("#hide_panel");
	var right_panel = $("#right_panel");
	
	if (button.hasClass("maximize")) {
		menu_panel.show();
		left_panel.show();
		hide_panel.css("left", hide_panel.attr("bkp_left"));
		right_panel.css("left", right_panel.attr("bkp_left"));
		button.removeClass("maximize").addClass("minimize");
	}
	else {
		hide_panel.attr("bkp_left", hide_panel.css("left"));
		right_panel.attr("bkp_left", right_panel.css("left"));
		
		menu_panel.hide();
		left_panel.hide();
		hide_panel.css("left", "0");
		right_panel.css("left", "5px");
		button.removeClass("minimize").addClass("maximize");
	}
}

//is used in the goTo function
function goToHandler(url, a, attr_name, originalEvent) {
	iframe_overlay.show();
	
	setTimeout(function() {
		try {
			$("#right_panel iframe")[0].src = url;
		}
		catch(e) {
			//sometimes gives an error bc of the iframe beforeunload event. This doesn't matter, but we should catch it and ignore it.
			if (console && console.log)
				console.log(e);
		}
	}, 100);
}

function goBack() {
	var iframe = $("#right_panel iframe")[0];
	var win = iframe.contentWindow;
	
	if (win)
		win.history.go(-1);
}

function refreshIframe() {
	$("#right_panel .iframe_overlay").show();
	
	var iframe = $("#right_panel iframe")[0];
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

function toggleTreeLayout(elm, tree_layout) {
	var url = ("" + document.location);
	url = url.replace(/(&?)tree_layout=[^&]*/g, "");
	url += "&tree_layout=" + tree_layout;
	url = url.replace(/[&]+/g, "&");
	
	document.location=url;
}

function filterByLayout(elm) {
	var proj_id = $(elm).val();
	var url = ("" + document.location);
	url = url.replace(/(&?)filter_by_layout=[^&]*/g, "");
	
	if (proj_id)
		url += "&filter_by_layout=" + proj_id;
	
	url = url.replace(/[&]+/g, "&");
	
	document.location=url;
}
