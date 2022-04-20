var iframe_overlay = null; //To be used by sub-pages

$(function() {
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
					if (jqXHR.status == 200 && jqXHR.responseText.indexOf('<div class="login">') > 0 && jqXHR.responseText.indexOf('<div id="layoutAuthentication">') > 0) 
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
			if (jqXHR.status == 200 && jqXHR.responseText.indexOf('<div class="login">') > 0 && jqXHR.responseText.indexOf('<div id="layoutAuthentication">') > 0)
				document.location = win_url;
	    	}
	});
	
	//prepare filter by layout field
	var select = $(".filter_by_layout select");
	var temp_elm = $("<span>" + select.find("option:selected").text() + "</span>");
	$("body").append(temp_elm);
	select.width(temp_elm.width() + 25);
	temp_elm.remove();
	
	//prepare hide_panel
	$("#hide_panel").draggable({
		axis: "x",
		appendTo: 'body',
		containment: $("#hide_panel").parent(),
		cursor: 'move',
		cancel: '.button',
		start : function(event, ui) {
			if ($(this).children(".button").hasClass("minimize")) {
				$('#left_panel, #hide_panel, #right_panel').addClass("dragging"); // We need to hide the iframe bc the draggable event has some problems with iframes
				return true;
			}
			return false;
		},
		drag : function(event, ui) {
			updatePanelsAccordingWithHidePanel();
		},
		stop : function(event, ui) {
			updatePanelsAccordingWithHidePanel();
			$('#left_panel, #hide_panel, #right_panel').removeClass("dragging");
		}
	});
	
	//prepare path_to_filter
	path_to_filter = path_to_filter.replace(/\/+/g, "/").replace(/^\//g, "").replace(/\/$/g, ""); //remove duplicated slashes and at the begin and at the end. Then converts path_to_filter to an array.
	var path_to_filter_exists = path_to_filter != "";
	
	var is_left_panel_with_tabs = $("#left_panel").is(".left_panel_with_tabs");
	
	if (!is_left_panel_with_tabs) {
		initFileTreeMenu();	//prepare menu tree
		
		//open pages li for the filtered project
		var file_tree_ul = $("#file_tree > ul");
		var lis = file_tree_ul.children("li");
		var item = lis.filter(".presentation_layers");
		
		if (path_to_filter_exists && item[0])
			openMainNodeTreeItemByPath(item, path_to_filter + "/src/entity");
		
		lis.filter(":not(.presentation_layers)").each(function(idx, li) {
			$(li).removeClass("jstree-open").addClass("jstree-closed");
		});
	}
	else { //change tree to be separated with tabs
		//prepare menu tree
		mytree.options.toggle_selection = false;
		mytree.options.ajax_callback_before = prepareLayerFileNodes1;
		mytree.options.ajax_callback_after = prepareLayerFileNodes2;
		
		initFileTreeMenu();
		
		//prepare file_tree with tabs
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
			var tab_label = "";
			
			if (classes.indexOf("main_node_presentation_layers") != -1)
				tab_label = "Interface";
			else if (classes.indexOf("main_node_business_logic_layers") != -1)
				tab_label = "Logic";
			else if (classes.indexOf("main_node_data_access_layers") != -1)
				tab_label = "SQL";
			else if (classes.indexOf("main_node_db_layers") != -1)
				tab_label = "DB";
			else if (classes.indexOf("main_node_library") != -1)
				tab_label = "Library";
			
			tabs_html	+= '<li class="' + tab_classes + '"><a href="#' + id + '" title="' + tab_label + '"><i class="tab_icon ' + classes + '"></i><span class="tab_label">' + tab_label + '</span></a></li>';
			
			li.addClass("main_tree_node");
		});
		
		tabs_html	+= '</ul>';
		
		file_tree_ul.prepend(tabs_html);
		file_tree_ul.tabs();
		
		file_tree_ul.find(" > ul.tabs > .tab_main_node a").click(function(idx, a){
			$(".jqcontextmenu").hide();
		});
		
		file_tree_ul.children("li.main_tree_node").each(function(idx, item) {
			item = $(item);
			item.addClass("hide_tree_item");
			
			var main_tree_node_label = item.find(" > a > label").text();
			
			if (item.is(".presentation_layers"))
				main_tree_node_label = "Interface - " + main_tree_node_label;
			else if (item.is(".data_access_layers"))
				main_tree_node_label = "SQL - " + main_tree_node_label;
			
			item.prepend('<div class="title">' + main_tree_node_label + '</div>');
			
			var main_ul = item.children("ul");
			var children = main_ul.children("li");
			
			//if multiple presentation layers and path_to_filter exists, remove the presentation layers that don't have the correspondent project
			if (children.length > 1 && item.is(".presentation_layers") && path_to_filter_exists) {
				var selected_bean_name = null;
				var selected_bean_file_name = null;
				
				for (var bean_name in main_layers_properties) {
					var layer_props = main_layers_properties[bean_name];
					var layer_bean_folder_name = layer_props["layer_bean_folder_name"];
					
					layer_bean_folder_name = layer_bean_folder_name.replace(/\/+/g, "/").replace(/^\//g, "").replace(/\/$/g, ""); //remove duplicated slashes and at the begin and at the end. Then converts path_to_filter to an array.
					
					if (path_to_filter.indexOf(layer_bean_folder_name) == 0) {
						selected_bean_name = bean_name;
						selected_bean_file_name = layer_props["bean_file_name"];
						break;
					}
				}
				
				if (selected_bean_name && selected_bean_file_name) {
					$.each(children, function(idx, child) {
						child = $(child);
						var child_ul_url = child.children("ul").attr("url");
						
						if (child_ul_url.indexOf("?bean_name=" + selected_bean_name + "&bean_file_name=" + selected_bean_file_name + "&") != -1) 
							child.addClass("jstree-last");
						else
							child.remove();
					});
					
					children = main_ul.children("li");
				}
			}
			
			//if layer is unique, then show only its content
			if (item.find(" > a > i").is(".main_node_management, .main_node_vendor")) {
				item.removeClass("jstree-closed").addClass("jstree-open hide_tree_item");
				
				iniSubMenu(item);
			}
			else if (children.length == 1) {
				var child = children.first();
				var sub_children = child.find(" > ul > li");
				
				item.removeClass("with_sub_groups");
				child.removeClass("jstree-closed").addClass("jstree-open hide_tree_item");
				
				if (sub_children.length == 0)
					mytree.refreshNodeChilds(child, {ajax_callback_last: function(ul, data) {
						//if path_to_filter exists and is main_node_presentation, the submenu is already inited with the right project node.
						if (!child.is(".main_node_presentation") || !path_to_filter_exists) {
							iniSubMenu(child);
							//console.log(child[0]);
						}
					}});
				else if (!child.is(".main_node_presentation") || !path_to_filter_exists) { //if path_to_filter exists and is main_node_presentation, the submenu is already inited with the right project node.
					iniSubMenu(child);
					//console.log(child[0]);
				}
			}
			else 
				item.addClass("with_sub_groups");
		});
	}
});

function prepareLayerFileNodes1(ul, data) {
	//filter data by path
	if (path_to_filter != "" && data && data.properties && data.properties["item_type"] == "presentation") {
		var path_to_filter_parts = path_to_filter.split("/");
		var path_to_filter_parts_idx = getPathToFilterPartsIndex($(ul).parent(), "#file_tree");
		data = prepareDataAccordingWithPathToFilterIndex(data, path_to_filter, path_to_filter_parts, path_to_filter_parts_idx);
	}
	
	//create nodes based in data
	prepareLayerNodes1(ul, data);
}

function prepareLayerFileNodes2(ul, data) {
	var path_to_filter_parts = null;
	var path_to_filter_parts_idx = null;
	var execute_filter = path_to_filter != "" && data && data.properties && data.properties["item_type"] == "presentation";
	
	//filter data by path
	if (execute_filter) {
		path_to_filter_parts = path_to_filter.split("/");
		path_to_filter_parts_idx = getPathToFilterPartsIndex($(ul).parent(), "#file_tree");
		data = prepareDataAccordingWithPathToFilterIndex(data, path_to_filter, path_to_filter_parts, path_to_filter_parts_idx);
	}
	
	//prepare created nodes based in data
	prepareLayerNodes2(ul, data);
	
	if (data) {
		ul = $(ul);
		
		//open sub nodes according with path_to_filter
		if (execute_filter && path_to_filter_parts_idx >= 0 && path_to_filter_parts.length > path_to_filter_parts_idx) {
			//check if exists list from data
			var data_contains_filtered_items = false;
			for (var k in data)
				if (k != "properties" && k != "aliases") {
					data_contains_filtered_items = true;
					break;
				}
			
			var lis = ul.children("li");
			
			if (data_contains_filtered_items) {
				lis.addClass("hide_tree_item");
				
				//make last node as primary node
				if (path_to_filter_parts_idx == path_to_filter_parts.length - 1) {
					//disable path_to_filter
					path_to_filter = "";
					
					//prepare project and open the entities_folder by default
					if (lis.find(" > a > i").is(".project, .project_common")) {
						lis.each(function(idx, li) {
							li = $(li);
							li.removeClass("jstree-closed").addClass("jstree-open with_sub_groups");
							
							iniSubMenu(li);
							
							//open project children
							mytree.refreshNodeChilds(li, {ajax_callback_last: function(ul, data) {
								ul = $(ul);
								var project_lis = ul.children();
								
								//open the pages li by default
								var pages_li = project_lis.find(" > a > i.entities_folder").parent().parent();
								refreshAndShowNodeChilds(pages_li);
							}});
							li.children("ul").show();
						});
					}
					else {
						//refresh created lis and show their sub-nodes
						lis.each(function(idx, li) {
							refreshAndShowNodeChilds( $(li) ); 
						});
					}
				}
				else {
					//refresh created lis and show their sub-nodes
					lis.each(function(idx, li) {
						refreshAndShowNodeChilds( $(li) ); 
					});
				}
			}
			else { //if there are no filtered childs
				lis.remove(); //delete all children
			}
		}
	}
}

function openMainNodeTreeItemByPath(main_node, path_to_filter) {
	var ul = main_node.children("ul");
	var children = ul.children("li");
	var selected_children = null;
	var path_to_search = null;
	
	//if multiple presentation layers and path_to_filter exists, remove the presentation layers that don't have the correspondent project
	if (children.length > 0) {
		var selected_bean_name = null;
		var selected_bean_file_name = null;
		
		for (var bean_name in main_layers_properties) {
			var layer_props = main_layers_properties[bean_name];
			var layer_bean_folder_name = layer_props["layer_bean_folder_name"];
			
			layer_bean_folder_name = layer_bean_folder_name.replace(/\/+/g, "/").replace(/^\//g, "").replace(/\/$/g, ""); //remove duplicated slashes and at the begin and at the end. Then converts path_to_filter to an array.
			
			if (path_to_filter.indexOf(layer_bean_folder_name) == 0) {
				selected_bean_name = bean_name;
				selected_bean_file_name = layer_props["bean_file_name"];
				path_to_search = path_to_filter.substr(layer_bean_folder_name.length, path_to_filter.length - layer_bean_folder_name.length);
				path_to_search = path_to_search.replace(/\/+/g, "/").replace(/^\//g, "").replace(/\/$/g, ""); //remove duplicated slashes and at the begin and at the end. Then converts path_to_filter to an array.
				break;
			}
		}
		
		if (selected_bean_name && selected_bean_file_name) {
			$.each(children, function(idx, child) {
				child = $(child);
				var child_ul_url = child.children("ul").attr("url");
				
				if (child_ul_url.indexOf("?bean_name=" + selected_bean_name + "&bean_file_name=" + selected_bean_file_name + "&") != -1) {
					selected_children = child;
					return false;
				}
			});
		}
	}
		
	if (selected_children && path_to_search)
		openTreeItemByPath(selected_children, path_to_search);
}

function openTreeItemByPath(li, path_to_search) {
	var ul = li.children("ul");
	var url = ul.attr("url");
	var m = url.match(/&path=([^&]*)/);
	
	if (m && m.length >= 2) {
		var path = m[1];
		path = path.replace(/\/+/g, "/").replace(/^\//g, "").replace(/\/$/g, ""); //remove duplicated slashes and at the begin and at the end. Then converts path_to_search to an array.
		var regex = new RegExp("^" + path);
		
		if (regex.test(path_to_search)) {
			var children = ul.children();
			var handler = function(sub_lis) {
				
				for (var i = 0; i < sub_lis.length; i++) {
					var sub_li = $(sub_lis[i]);
					
					var found = openTreeItemByPath(sub_li, path_to_search);
					
					if (found)
						break;
				}
			};
			
			li.removeClass("jstree-closed").addClass("jstree-open");
			
			if (children.length == 0)
				mytree.refreshNodeChilds(li, {ajax_callback_last: function(sub_ul, data) {
					var sub_children = $(sub_ul).children();
					handler(sub_children);
				}});
			else
				handler(children);
			
			return true;
		}
	}
	
	return false;
}

function iniSubMenu(tree_item_li) {
	var a = tree_item_li.children("a");
	var context_menu_id = a.data("context_menu_id");
	var context_menu_options = a.data("context_menu_options");
	
	if (context_menu_id && $.isPlainObject(context_menu_options) && typeof context_menu_options.callback == "function") {
		var main_tree_node = tree_item_li.closest(".main_tree_node");
		var main_tree_node_id = main_tree_node.attr("id");
		var tabs = main_tree_node.parent().children("ul.tabs");
		var tab_link = tabs.find("li > a[href='#" + main_tree_node_id + "']");
		var context_menu_elm = $("#" + context_menu_id);
		//console.log(tree_item_li[0]);
		//console.log(main_tree_node[0]);
		//console.log(tab_link[0]);
		
		var html = '<span class="icon sub_menu" title="Open context menu"></span>';
		var func = function(e) {
			e.preventDefault();
			e.stopPropagation();
			
			//show or hide contextmenu
			if (context_menu_elm.is(":visible"))
				jquerycontextmenu.hidebox(jQuery, context_menu_elm);
			else {
				a.children("label").contextmenu();
				jquerycontextmenu.positionul(jQuery, context_menu_elm, e);
			}
			
			return false;
		};
		
		var sub_menu_icon_1 = $(html);
		tab_link.parent().append(sub_menu_icon_1);
		sub_menu_icon_1.click(func);
		
		var sub_menu_icon_2 = $(html);
		main_tree_node.children(".title").append(sub_menu_icon_2);
		sub_menu_icon_2.click(func);
		
		/*if (tree_item_li.find(" > a > i").is(".project, .project_common")) {
			var level_menu = $('<li class="level">'
							+ '<label>Level:</label>'
							+ '<select onChange="toggleComplexityLevel(this)">'
								+ '<option value="0">Basic</option>'
								+ '<option value="1">Advanced</option>'
							+ '</select>'
						+ '</li>');
			
			context_menu_elm.append('<li class="line_break"></li>');
			context_menu_elm.append(level_menu);
		}*/
	}
}

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
	//var menu_panel = $("#menu_panel"); //menu panel is now a fixed top bar
	var left_panel = $("#left_panel");
	var hide_panel = $("#hide_panel");
	var right_panel = $("#right_panel");
	
	var left = parseInt(hide_panel.css("left"));
	left = left < 50 ? 50 : (left > $(window).width() - 50 ? $(window).width() - 50 : left);
	
	//menu_panel.css("width", left + "px");
	left_panel.css("width", left + "px");
	hide_panel.css("left", left + "px");
	right_panel.css("left", (left + 5) + "px"); //5 is the width of the hide_panel
}

function toggleLeftPanel(elm) {
	button = $(elm);
	
	//var menu_panel = $("#menu_panel"); //menu panel is now a fixed top bar
	var left_panel = $("#left_panel");
	var hide_panel = $("#hide_panel");
	var right_panel = $("#right_panel");
	
	if (button.hasClass("maximize")) {
		//menu_panel.show();
		left_panel.show();
		hide_panel.css("left", hide_panel.attr("bkp_left"));
		right_panel.css("left", right_panel.attr("bkp_left"));
		button.removeClass("maximize").addClass("minimize");
	}
	else {
		hide_panel.attr("bkp_left", hide_panel.css("left"));
		right_panel.attr("bkp_left", right_panel.css("left"));
		
		//menu_panel.hide();
		left_panel.hide();
		hide_panel.css("left", "0");
		right_panel.css("left", "5px");
		button.removeClass("minimize").addClass("maximize");
	}
}

function toggleAdvancedLevel(elm) {
	var left_panel = $("#left_panel");
	var advanced_level = left_panel.hasClass("advanced_level") ? "simple_level" : "advanced_level";
	
	MyJSLib.CookieHandler.setCookie('advanced_level', advanced_level, 0, "/");
	left_panel.toggleClass("simple_level").toggleClass("advanced_level");
	
	$(elm).children("span").html(advanced_level == "advanced_level" ? "Show basic items" : "Show advanced items");
	
	//check if active tab is a hidden tab
	var tabs_parent = left_panel.find(" > .mytree > ul");
	var active_tab = tabs_parent.tabs("option", "active");
	var li = $(tabs_parent.find(" > ul > li")[active_tab]);
	
	if (!li.is(":visible"))
		tabs_parent.tabs("option", "active", tabs_parent.find(" > ul > li:visible").first().index());
}

function toggleTreeLayout(elm) {
	var left_panel = $("#left_panel");
	var tree_layout = left_panel.hasClass("left_panel_with_tabs") ? "left_panel_without_tabs" : "left_panel_with_tabs";
	var url = ("" + document.location);
	url = url.replace(/(&?)tree_layout=[^&]*/g, "");
	url += (url.indexOf("?") != -1 ? "&" : "?") + "tree_layout=" + tree_layout;
	url = url.replace(/[&]+/g, "&");
	
	document.location = url;
}

function toggleThemeLayout(elm) {
	var left_panel = $("#left_panel");
	var theme_layout = left_panel.hasClass("light_theme") ? "dark_theme" : "light_theme";
	
	MyJSLib.CookieHandler.setCookie('theme_layout', theme_layout, 0, "/");
	left_panel.toggleClass("dark_theme").toggleClass("light_theme");
	$(".top_panel").toggleClass("dark_theme").toggleClass("light_theme");
	$("body").removeClass(theme_layout == "dark_theme" ? "light_theme" : "dark_theme").addClass(theme_layout);
	
	$(elm).children("span").html(theme_layout == "dark_theme" ? "Show light theme" : "Show dark theme");
	
	updateThemeLayoutInIframes( $("iframe"), theme_layout);
}

function updateThemeLayoutInIframes(iframes, theme_layout) {
	iframes.each(function(idx, iframe) {
		//Note that if the iframe is from other domain, we cannot edit the iframe's html. So we need to have this enclosed in a try and catch.
		//Another situation that it can throw an exception, is if the iframe is not loaded yet.
		try { 
			var iframe_window = iframe.contentWindow; //we can get the reference to the inner window
			var iframe_doc = iframe.contentDocument || iframe_window.document; //...but we cannot get the reference to the document inside of an iframe from a different domain. By doing this code, it will launch an exception, not executing the rest of the code.
			var iframe_body = $(iframe_doc.body);
			//var iframe_body = $(iframe).contents().find("body");
			
			iframe_body.removeClass(theme_layout == "dark_theme" ? "light_theme" : "dark_theme").addClass(theme_layout);
			
			if (iframe_body.hasClass(theme_layout)) //be sure that the body has the class and that we can really edit the iframe body, bc if the iframe is from other domain, we cannot edit the iframe's html.
				updateThemeLayoutInIframes(iframe_body.find("iframes"), theme_layout);
		}
		catch(e) {
			console.log(e);
		};
	});
}

function filterByLayout(elm) {
	$(elm).css("width", "");
	var proj_id = $(elm).val();
	var url = ("" + document.location);
	url = url.replace(/(&?)filter_by_layout=[^&]*/g, "");
	url += (url.indexOf("?") != -1 ? "&" : "?") + "filter_by_layout=" + proj_id;
	url = url.replace(/[&]+/g, "&");
	
	document.location = url;
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
