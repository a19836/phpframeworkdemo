$(function () {
	initObjectBlockSettings("menu_settings", saveMenu, "saveMenu");
	
	$(".menu_settings input[type=color]").each(function(idx, input) {
		input = $(input);
		input.on("input", function(event) {
			$(this).parent().children("input[type=text]").val( this.value );
		});
		
		input.parent().children("input[type=text]").blur(function() {
			$(this).parent().children("input[type=color]")[0].value = $(this).val();
		});
	});
});

function setDefaultTemplatePTL() {
	var r = Math.floor(Math.random() * 1000);
	
	/*var ptl =   '<ptl:if !function_exists("getMenusHTML_' + r + '")>' +
		"\n" + '	<!--ul-->' +
		"\n" + '	' +
		"\n" + '	<ptl:function:getMenusHTML_' + r + ' list_class menus>' +
		"\n" + '		<ptl:if is_array(\\$menus)>' +
		"\n" + '			<ptl:foreach \\$menus i item>' +
		"\n" + '				<li class="<ptl:echo \\$item[class]/>" title="<ptl:echo \\$item[title]/>" <ptl:echo \\$item[attrs]/> >' +
		"\n" + '					<ptl:echo \\$item[previous_html]/>' +
		"\n" + '					' +
		"\n" + '					<a href="<ptl:if \\$item[url]><ptl:echo \\$item[url]/><ptl:else><ptl:echo \'javascript:void(0)\'/></ptl:if>">' +
		"\n" + '						<label><ptl:echo \\$item[label]/></label>' +
		"\n" + '					</a>' +
		"\n" + '					' +
		"\n" + '					<ptl:if is_array(\\$item[menus])>' +
		"\n" + '						<ul class="<ptl:echo \\$list_class/>">' +
		"\n" + '							<ptl:getMenusHTML_' + r + ' \\$list_class \\$item[menus]>' +
		"\n" + '						</ul>' +
		"\n" + '					</ptl:if>' +
		"\n" + '					' +
		"\n" + '					<ptl:echo \\$item[next-html]/>' +
		"\n" + '				</li>' +
		"\n" + '			</ptl:foreach>' +
		"\n" + '		</ptl:if>' +
		"\n" + '	</ptl:function>' +
		"\n" + '	' +
		"\n" + '	<!--/ul-->' +
		"\n" + '</ptl:if>' +
		"\n" + '' +
		"\n" + '<ul class="<ptl:echo \\$list_class/>">' +
		"\n" + '	<ptl:getMenusHTML_' + r + ' \\$list_class \\$input>' +
		"\n" + '</ul>';*/
	
	var ptl =   '<ptl:if !function_exists("getMenuItemHTML_' + r + '")>' +
		"\n" + '	<ptl:function:getMenuItemHTML_' + r + ' list_class item prefix>' +
		"\n" + '		<ptl:if is_array(\\$item)>' +
		"\n" + '			<li class="nav-item <ptl:echo \\$item[class]/>" <ptl:echo \\$item[attrs]/> >' +
		"\n" + '				<ptl:echo \\$item[previous_html]/>' +
		"\n" + '				' +
		"\n" + '				<ptl:if !empty(\\$item[menus]) && is_array(\\$item[menus])>' +
		"\n" + '					<a class="nav-link dropdown-item collapsed" href="javascript:void(0)" data-toggle="collapse" data-toggle="collapse" data-target="#collapseLayouts-<ptl:echo \\$prefix/>" aria-expanded="false" aria-controls="collapseLayouts-<ptl:echo \\$prefix/>" title="<ptl:echo \\$item[title]/>">' +
		"\n" + '						<span class="sb-nav-link-icon"><i class="fas fa-table"></i></span>' +
		"\n" + '						<span><ptl:echo \\$item[label]/></span>' +
		"\n" + '						<span class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></span>' +
		"\n" + '					</a>' +
		"\n" + '					<ptl:echo \\$item[next_html]/>' +
		"\n" + '					<div class="collapse" id="collapseLayouts-<ptl:echo \\$prefix/>" aria-labelledby="headingOne">' +
		"\n" + '						<ul class="sb-sidenav-menu-nested nav flex-column ml-0 <ptl:echo \\$list_class/>">' +
		"\n" + '							<ptl:foreach \\$item[menus] j sub_item>' +
		"\n" + '								<ptl:getMenuItemHTML_' + r + ' \\$list_class \\$sub_item "{\\$prefix}_\\$j">' +
		"\n" + '							</ptl:foreach>' +
		"\n" + '						</ul>' +
		"\n" + '					</div>' +
	    	"\n" + '				<ptl:else>' +
	    	"\n" + '					<a class="nav-link dropdown-item" href="<ptl:if \\$item[url]><ptl:echo \\$item[url]/><ptl:else><ptl:echo \'javascript:void(0)\'/></ptl:if>" title="<ptl:echo \\$item[title]/>">' +
		"\n" + '						<span class="sb-nav-link-icon"><i class="fas fa-table"></i></span>' +
		"\n" + '						<span><ptl:echo \\$item[label]/></span>' +
		"\n" + '					</a>' +
	    	"\n" + '					<ptl:echo \\$item[next_html]/>' +
		"\n" + '				</ptl:if>' +
		"\n" + '			</li>' +
		"\n" + '		</ptl:if>' +
		"\n" + '	</ptl:function>' +
		"\n" + '</ptl:if>' +
		"\n" + '' +
		"\n" + '<ul class="nav flex-column <ptl:echo \\$list_class/>">' +
		"\n" + '	<ptl:if is_array(\\$input)>' +
		"\n" + '		<ptl:foreach \\$input i item>' +
		"\n" + '			<ptl:getMenuItemHTML_' + r + ' \\$list_class \\$item \\$i>' +
		"\n" + '		</ptl:foreach>' +
		"\n" + '	</ptl:if>' +
		"\n" + '</ul>' +
		"\n" + '' +
		"\n" + '<script>' +
		"\n" + '	if (typeof overwriteBootstrapNavSubMenuToggle != "function") ' +
		"\n" + '	    function overwriteBootstrapNavSubMenuToggle(list_class) {' +
		"\n" + '		   window.addEventListener("load", function() {' +
		"\n" + '			  var menus_selector = ".module_menu > " + list_class + " .nav-item a[data-toggle=collapse][data-target]";' +
		"\n" + '			  ' +
		"\n" + '			  if (typeof jQuery == "function" && typeof $ == "function") {' +
		"\n" + '		   	   $(menus_selector).each(function(idx, item) {' +
		"\n" + '		   	       item = $(item);' +
		"\n" + '		   	       ' +
		"\n" + '		   	       if (item.data("bs_menu_click_event_init") != 1) {' +
		"\n" + '		   	           item.data("bs_menu_click_event_init", 1);' +
		"\n" + '		   	           ' +
		"\n" + '		   	           item.on("click", function(e) {' +
		"\n" + '		   	               e.stopPropagation();' +
		"\n" + '		   	               ' +
		"\n" + '		   	               var selector = item.attr("data-target");' +
		"\n" + '		   	               var target = item.parent().find(selector);' +
		"\n" + '		   	               ' +
		"\n" + '		   	               if (target[0])' +
		"\n" + '				   	          target.toggle({' +
		"\n" + '			   	                   duration: "slow", ' +
		"\n" + '			   	                   start: function() {' +
		"\n" + '			   	                       target.addClass("collapsing show");' +
		"\n" + '			   	                       target.removeClass("collapse");' +
		"\n" + '			   	                   },' +
		"\n" + '			   	                   complete: function() {' +
		"\n" + '			   	                       target.removeClass("collapsing show");' +
		"\n" + '			   	                       ' +
		"\n" + '			   	                       if (target.css("display") == "none")' +
		"\n" + '			   	                           target.addClass("collapse");' +
		"\n" + '			   	                       else' +
		"\n" + '			   	                           target.removeClass("collapse");' +
		"\n" + '			   	                       ' +
		"\n" + '			   	                       target.css("display", "");' +
		"\n" + '			   	                   }' +
		"\n" + '			   	               });' +
		"\n" + '		   	           });' +
		"\n" + '		   	       }' +
		"\n" + '		   	   });' +
		"\n" + '			  }' +
		"\n" + '			  else {' +
		"\n" + '		   	   var items = document.querySelectorAll(menus_selector);' +
		"\n" + '		   	   ' +
		"\n" + '		   	   for (var i = 0, t = items.length; i < t; i++) {' +
		"\n" + '		   	       var item = items[i];' +
		"\n" + '		   	       ' +
		"\n" + '		   	       if (item.bs_menu_click_event_init != 1) {' +
		"\n" + '		   	           item.bs_menu_click_event_init = 1;' +
		"\n" + '		   	           ' +
		"\n" + '		   	           item.addEventListener("click", function(e) {' +
		"\n" + '		   	               e.stopPropagation();' +
		"\n" + '		   	               ' +
		"\n" + '		   	               var selector = this.getAttribute("data-target");' +
		"\n" + '		   	               ' +
		"\n" + '		   	               if (selector) {' +
		"\n" + '		   	                   var p = this.parentNode;' +
		"\n" + '		   	                   var target = p.querySelector(selector);' +
		"\n" + '		   	                   ' +
		"\n" + '		   	                   if (target)' +
		"\n" + '		   	                       target.classList.toggle("collapse");' +
		"\n" + '		   	               }' +
		"\n" + '		   	           });' +
		"\n" + '		   	       }' +
		"\n" + '		   	   }' +
		"\n" + '			  }' +
		"\n" + '		   });' +
		"\n" + '	    }' +
		"\n" + '	' +
		"\n" + '	overwriteBootstrapNavSubMenuToggle(".<ptl:echo str_replace(" ", ".", preg_replace("/ +/", " ", trim(\\$list_class)))/>");' +
		"\n" + '</script>';
	
	$(".menu_settings .els > .ptl > .layout-ui-editor > .template-source > textarea").text(ptl);
	
	var css = "a.nav-link { color:inherit; }";
	var editor_parent = $(".menu_settings .block_css");
	var editor = editor_parent.data("editor");
	if (editor)
		editor.setValue(css);
	else
		editor_parent.children("textarea.css").first().val(css);
}

function loadShowMenuBlockSettings(settings_elm, settings_values) {
	//console.log(settings_values);
	var menu_settings = settings_elm.children(".menu_settings");
	var selected_menu_select_field = menu_settings.children(".menu_items").children(".selected_menu").children("select");
	
	if(!settings_values || $.isEmptyObject(settings_values))
		setDefaultTemplatePTL();
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_data"),
		success: function(data) {
			if (data) {
				var object_types = data["object_types"];
				var menu_groups = data["menu_groups"];
				
				var options = '';
				$.each(object_types, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				menu_settings.children(".menu_items").children(".menu_by_parent").children(".menu_parent_object_type_id").children("select").html(options);
				
				options = '';
				$.each(menu_groups, function(index, menu_group) {
					options += '<option value="' + menu_group["group_id"] + '">' + menu_group["name"] + '</option>';
				});
				
				selected_menu_select_field.html(options);
			}
			else {
				StatusMessageHandler.showError("There are no Menus in the DB. Please create some menus first...");
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load menu groups from DB.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	var task_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
	
	if (task_values.hasOwnProperty("menus") && task_values["menus"]) {
		var ul = menu_settings.children(".menu_items").children(".items");
		loadMenuBlockSettingsItems(ul, task_values["menus"]);
	}
	
	loadObjectBlockSettings(settings_elm, settings_values, "menu_settings");
	loadTaskPTLFieldsBlockSettings(settings_elm, settings_values, "menu_settings");
	
	if (task_values.hasOwnProperty("menu_group_id") && task_values["menu_group_id"] && selected_menu_select_field.val() != task_values["menu_group_id"]) {
		menu_settings.find(" > .menu_items > .query_type > select").val("user_defined");
		menu_settings.find(" > .menu_items > .menu_by_user_defined > input").val( task_values["menu_group_id"] );
	}
	
	if (task_values.hasOwnProperty("menu_background_color"))
		menu_settings.find(".menu_background_color input[type=color]")[0].value = task_values["menu_background_color"];
	
	if (task_values.hasOwnProperty("menu_text_color"))
		menu_settings.find(".menu_text_color input[type=color]")[0].value = task_values["menu_text_color"];
	
	if (task_values.hasOwnProperty("sub_menu_background_color"))
		menu_settings.find(".sub_menu_background_color input[type=color]")[0].value = task_values["sub_menu_background_color"];
	
	if (task_values.hasOwnProperty("sub_menu_text_color"))
		menu_settings.find(".sub_menu_text_color input[type=color]")[0].value = task_values["sub_menu_text_color"];
	
	onChangeMenuItemsType( menu_settings.children(".menu_items").children("select.items_type")[0] );
	onChangeTemplateType( menu_settings.children(".template_type").children("select")[0] );
}

function loadMenuBlockSettingsItems(ul, settings_items) {
	if (settings_items && ($.isArray(settings_items) || $.isPlainObject(settings_items))) {
		$.each(settings_items, function(idx, si) {
			var menu = addItemToUl(ul, {
				"label": /*prepareFieldValueIfValueTypeIsString(*/si["label"]/*)*/,
				"url": /*prepareFieldValueIfValueTypeIsString(*/si["url"]/*)*/,
				"title": /*prepareFieldValueIfValueTypeIsString(*/si["title"]/*)*/,
				"class": /*prepareFieldValueIfValueTypeIsString(*/si["class"]/*)*/,
				"attrs": /*prepareFieldValueIfValueTypeIsString(*/si["attrs"]/*)*/,
				"previous_html": /*prepareFieldValueIfValueTypeIsString(*/si["previous_html"]/*)*/,
				"next_html": /*prepareFieldValueIfValueTypeIsString(*/si["next_html"]/*)*/,
			});
			
			if (si.hasOwnProperty("menus") && si["menus"]) {
				var sub_ul = menu.children(".items");
				
				loadMenuBlockSettingsItems(sub_ul, si["menus"]);
			}
		});
	}
}

function onChangeTemplateType(elm) {
	elm = $(elm);
	var type = elm.val();
	
	if (type == "user_defined")
		elm.parent().closest(".menu_settings").children(".els").show();
	else
		elm.parent().closest(".menu_settings").children(".els").hide();
}

function onChangeMenuItemsType(elm) {
	elm = $(elm);
	var p = elm.parent();
	
	if (elm.val() == "from_db") {
		p.children(".icon, .items").hide();
		p.children(".query_type, .menu_items_default_settings").show();
		
		onChangeMenuItemsQueryType( p.children(".query_type").children("select")[0] );
	}
	else {
		p.children(".icon, .items").show();
		p.children(".query_type, .selected_menu, .menu_by_user_defined, .menu_by_tag, .menu_by_parent, .menu_items_default_settings").hide();
	}
}

function onChangeMenuItemsQueryType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var p = elm.parent().parent();
	var menu_by_parent = p.children(".menu_by_parent");
	
	if (value == "selected_menu") {
		p.children(".selected_menu").show();
		p.children(".menu_by_user_defined, .menu_by_tag").hide();
		menu_by_parent.hide();
	}
	else if (value == "first_menu_by_tag_and" || value == "first_menu_by_tag_or") {
		p.children(".menu_by_tag").show();
		p.children(".selected_menu, .menu_by_user_defined").hide();
		menu_by_parent.hide();
	}
	else if (value == "first_menu_by_parent") {
		p.children(".selected_menu, .menu_by_user_defined, .menu_by_tag").hide();
		menu_by_parent.show();
		menu_by_parent.children(".menu_parent_group").hide();
	}
	else if (value == "first_menu_by_parent_group") {
		p.children(".selected_menu, .menu_by_user_defined, .menu_by_tag").hide();
		menu_by_parent.show();
		menu_by_parent.children(".menu_parent_group").show();
	}
	else if (value == "user_defined") {
		p.children(".menu_by_user_defined").show();
		p.children(".selected_menu, .menu_by_tag").hide();
		menu_by_parent.hide();
	}
}

function addMainItem(elm) {
	var ul = $(elm).parent().children(".items");
	return addItemToUl(ul);
}

function addSubItem(elm, prefix) {
	var ul = $(elm).parent().parent().children(".items");
	return addItemToUl(ul);
}

function addItemToUl(ul, default_values) {
	default_values = default_values ? default_values : {};
	var label = default_values["label"] ? default_values["label"].replace(/"/g, "&quot;") : "";
	var url = default_values["url"] ? default_values["url"].replace(/"/g, "&quot;") : "";
	var title = default_values["title"] ? default_values["title"].replace(/"/g, "&quot;") : "";
	var clas = default_values["class"] ? default_values["class"].replace(/"/g, "&quot;") : "";
	var attrs = default_values["attrs"] ? default_values["attrs"].replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;") : "";
	var previous_html = default_values["previous_html"] ? default_values["previous_html"].replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;") : "";
	var next_html = default_values["next_html"] ? default_values["next_html"].replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;") : "";
	
	var prefix = ul.attr("prefix");
	
	var idx = $(ul).attr('li_counter');
	
	if (!idx || idx <= 0) {
		idx = $(ul).children().length;
	}
	else
		++idx;
	
	$(ul).attr('li_counter', idx);
	
	prefix = prefix + "[" + idx + "]";
	var html = $(menu_item_html.replace(/#prefix#/g, prefix).replace(/#label#/g, label).replace(/#url#/g, url).replace(/#title#/g, title).replace(/#class_name#/g, clas).replace(/#attrs#/g, attrs).replace(/#previous_html#/g, previous_html).replace(/#next_html#/g, next_html));
	
	ul.append(html);
	ul.show();
	
	return html;
}

function toggleMenuItemProperties(elm) {
	elm = $(elm);
	var item = elm.parent().parent();
	
	var props = item.children(".menu_url, .menu_title, .menu_class, .menu_attrs, .menu_previous_html, .menu_next_html");
	
	if (props.css("display") == "block") {
		props.css("display", "none");
		elm.removeClass("minimize").addClass("maximize");
	}
	else {
		props.css("display", "block");
		elm.removeClass("maximize").addClass("minimize");
	}
}

function deleteMenuItemProperties(elm) {
	$(elm).parent().parent().remove();
}

function moveUpMenuItem(elm) {
	moveMenuItem(elm, "up");
}

function moveDownMenuItem(elm) {
	moveMenuItem(elm, "down");
}

function moveMenuItem(elm, direction) {
	var item = $(elm).parent().parent();
	
	if (direction == "up") {
		if (item.prev()[0])
			item.parent()[0].insertBefore(item[0], item.prev()[0]);
	}
	else {
		if (item.next()[0])
			item.parent()[0].insertBefore(item.next()[0], item[0]);
	}
	
	updateMenuItemsIndexes(item.parent());
}

function updateMenuItemsIndexes(main_ul) {
	var main_prefix = main_ul.attr("prefix");
	
	main_ul.children(".item").each(function(i, elm) {
		elm = $(elm);
		var ul = elm.children("ul");
		
		var prefix = ul.attr("prefix");
		prefix = prefix.substr(0, prefix.length - ("[menus]").length);
		var new_prefix = main_prefix + "[" + (i + 1) + "][menus]";
		ul.attr("prefix", new_prefix);
		
		elm.children(".menu_label, .menu_url, .menu_title, .menu_class, .menu_attrs, .menu_previous_html, .menu_next_html").children("input, select, textarea").each(function(j, prop) {
			prop = $(prop);
			var name = prop.attr("name");
			var new_name = main_prefix + "[" + (i + 1) + "]" + name.substr(prefix.length);
			prop.attr("name", new_name);
		});
		
		updateMenuItemsIndexes(ul);
	});
}

function saveMenu(button) {
	var menu_items = $(".block_obj > .module_settings > .settings > .menu_settings > .menu_items");
	var query_type = menu_items.find(" > .query_type > select").val();
	var select = menu_items.find(" > .selected_menu > select");
	var input = menu_items.find(" > .menu_by_user_defined > input");
	
	if (query_type == "user_defined") {
		input.addClass("module_settings_property");
		select.removeClass("module_settings_property");
	}
	else {
		input.removeClass("module_settings_property");
		select.addClass("module_settings_property");
	}
	
	saveObjectBlock(button, "menu_settings");
}
