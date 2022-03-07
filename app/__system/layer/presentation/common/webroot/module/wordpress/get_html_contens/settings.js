var wordpress_installation_data = {};
var is_default_values_loaded = false;

$(function () {
	initObjectBlockSettings("module_get_html_contents_settings", saveGetHtmlContents, "saveGetHtmlContents");
	
	//create editor for previous_html and next_html
	createPreviousAndNextHtmlEditores( $(".module_get_html_contents_settings") );
});

function loadGetHtmlContentsBlockSettings(settings_elm, settings_values) {
	loadObjectBlockSettings(settings_elm, settings_values, "module_get_html_contents_settings");
	
	var block_settings = settings_elm.children(".module_get_html_contents_settings");
	
	//check if there is any wordpress installation
	if (block_settings.find(" > .wordpress_installation_name > select option").length == 1)
		alert("Note that there is not any WordPress installation yet.\nPlease install the wordpress first...");
	
	var tasks_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
	
	if (!tasks_values || $.isEmptyObject(tasks_values))
		is_default_values_loaded = true;
	
	setGetHtmlContentsSelectFieldValue( block_settings.find(" > .wordpress_installation_name select"), tasks_values["wordpress_installation_name"] ); //load wordpress_installation_name. Must set the wordpress_installation_name here before the onChangeWordPressDBDriver runs, bc the onChangeWordPressWidget gets the wordpress_installation_name and it must be set before.
	
	var page_type_select = block_settings.find(" > .page_type select");
	var page_type = page_type_select.val();
	onChangePageType( page_type_select[0] );
	
	onChangeThemeType( block_settings.find(" > .theme_type select")[0] );
	
	if (tasks_values && !$.isEmptyObject(tasks_values)) {
		setGetHtmlContentsSelectFieldValue( block_settings.find(" > .wordpress_theme select"), tasks_values["wordpress_theme"] ); //load wordpress_theme. Must set the wordpress_theme here before the onChangeWordPressDBDriver runs.
		
		block_settings.find(" > .previous_html textarea.html").val(tasks_values["previous_html"]);
		block_settings.find(" > .next_html textarea.html").val(tasks_values["next_html"]);
		
		switch (page_type) {
			case "page":
				setGetHtmlContentsSelectFieldValue( block_settings.find(" > .page select"), tasks_values["page"] ); //load page
				break;
			
			case "posts_by_category":
				setGetHtmlContentsSelectFieldValue( block_settings.find(" > .posts_by_category select"), tasks_values["posts_category"] ); //load category
				break;
			
			case "posts_by_tag":
				setGetHtmlContentsSelectFieldValue( block_settings.find(" > .posts_by_tag select"), tasks_values["posts_tag"] ); //load tag
				break;
			
			case "posts_by_date":
				block_settings.find(" > .posts_by_date input").val(tasks_values["posts_date"]); //load date
				break;
			
			case "posts_by_existent_date":
				setGetHtmlContentsSelectFieldValue( block_settings.find(" > .posts_by_existent_date select"), tasks_values["posts_existent_date"] ); //load existent date
				break;
			
			case "posts_by_id":
				setGetHtmlContentsSelectFieldValue( block_settings.find(" > .posts_by_id select"), tasks_values["post_id"] ); //load id
				break;
		}
		
		//prepare blocks
		if (tasks_values["blocks"]) {
			if (!$.isPlainObject(tasks_values["blocks"]) && !$.isArray(tasks_values["blocks"]))
				tasks_values["blocks"] = [ tasks_values["blocks"] ];
			
			var block_type_select = block_settings.find(" > .block_type > select");
			var wordpress_blocks = block_settings.children(".wordpress_blocks");
			wordpress_blocks.children(".no_wordpress_blocks").hide();
			
			$.each(tasks_values["blocks"], function(idx, block) {
				var block_type = block["block_type"];
				var block_label = block_type_select.children("option[value='" + block_type + "']").text();
				var html = getWordPressBlockHtml(block_label, block_type, idx);
				
				html = $(html);
				wordpress_blocks.append(html);
				
				html.find(".previous_html textarea.html").val(block["previous_html"]);
				html.find(".next_html textarea.html").val(block["next_html"]);
				
				switch (block_type) {
					case "full_page_html":
					case "header":
					case "footer":
					case "content":
						if (block["exclude_theme_side_bars"])
							html.find(".exclude_theme_side_bars input").attr("checked", "checked").prop("checked", true);
						
						if (block["exclude_theme_menus"])
							html.find(".exclude_theme_menus input").attr("checked", "checked").prop("checked", true);
						
						if (block["exclude_theme_comments"])
							html.find(".exclude_theme_comments input").attr("checked", "checked").prop("checked", true);
						
						if (block["convert_html_into_inner_html"])
							html.find(".convert_html_into_inner_html input").attr("checked", "checked").prop("checked", true);
						
						if (block_type == "full_page_html") {
							if (block["exclude_theme_before_header"])
								html.find(".exclude_theme_before_header input").attr("checked", "checked").prop("checked", true);
							
							if (block["exclude_theme_header"])
								html.find(".exclude_theme_header input").attr("checked", "checked").prop("checked", true);
							
							if (block["exclude_theme_footer"])
								html.find(".exclude_theme_footer input").attr("checked", "checked").prop("checked", true);
							
							if (block["exclude_theme_after_footer"])
								html.find(".exclude_theme_after_footer input").attr("checked", "checked").prop("checked", true);
						}
						else {
							var select = html.find(".html_type select");
							select.val(block["html_type"]);
							onChangeHtmlType(select[0]);
						}
						
						break;
					
					case "post_comments":
						var select = html.find(".post_comments select");
						select.val(block["post_comments"]); //load comments
						onChangePostCommentsType(select[0]);
						
						if (block["get_directly_from_theme"])
							html.find(".get_directly_from_theme input").attr("checked", "checked").prop("checked", true);
						
						break;
					
					case "widget":
						var widget_select = html.find(".widget select");
						setGetHtmlContentsSelectFieldValue( widget_select, block["widget"] ); //load widget
						
						if (block["widget_args"])
							$.each(block["widget_args"], function(arg_name, arg_value) {
								html.find(".widget_args .widget_arg_" + arg_name + " input").val(arg_value);
							});
						
						onChangeWordPressWidget(widget_select[0], block["widget_options"]);
						break;
					
					case "side_bar":
						setGetHtmlContentsSelectFieldValue( html.find(".side_bar select"), block["side_bar"] ); //side bar
						
						if (block["get_directly_from_theme"])
							html.find(".get_directly_from_theme input").attr("checked", "checked").prop("checked", true);
						
						break;
					
					case "menu":
						setGetHtmlContentsSelectFieldValue( html.find(".menu select"), block["menu"] ); //menu
						
						if (block["get_directly_from_theme"])
							html.find(".get_directly_from_theme input").attr("checked", "checked").prop("checked", true);
						
						break;
					
					case "menu_location":
						setGetHtmlContentsSelectFieldValue( html.find(".menu_location select"), block["menu_location"] ); //load menu_location
						
						if (block["get_directly_from_theme"])
							html.find(".get_directly_from_theme input").attr("checked", "checked").prop("checked", true);
						
						break;
					
					case "code":
						block["code"] = prepareFieldValueIfValueTypeIsString(block["code"]); //remove extra quotes that were added by the convertBlockSettingsValuesIntoBasicArray function
						
						var textarea = html.find(".code textarea");
						textarea.val( block["code"] );
						createObjectItemCodeEditor(textarea[0], "php", saveGetHtmlContents);
						break;
				}
			});
		}
		
		//prepare allowed_wordpress_urls
		if (tasks_values["allowed_wordpress_urls"]) {
			var add_icon = block_settings.find(" > .wordpress_options > .allowed_wordpress_urls > label > .add")[0];
			
			if (!$.isPlainObject(tasks_values["allowed_wordpress_urls"]) && !$.isArray(tasks_values["allowed_wordpress_urls"]))
				tasks_values["allowed_wordpress_urls"] = [ tasks_values["allowed_wordpress_urls"] ];
			
			$.each(tasks_values["allowed_wordpress_urls"], function(idx, url) {
				var item = addWordPressUrlToExcludeConversion(add_icon);
				item.find("input").val(url);
			});
		}
	}
	
	onCheckParseWordPressUrls( block_settings.find(" > .wordpress_options > .parse_wordpress_urls > input")[0] );
	onChangeWordPressDBDriver( block_settings.find(" > .wordpress_installation_name > select")[0] );
}

function setGetHtmlContentsSelectFieldValue(select, value, text) {
	select.val(value);
	
	if (value != null && select.val() != value) {
		var extra = " - INVALID VALUE";
		
		if (text && text.substr(text.length - extra.length) == extra) //remove extra string
			text = text.substr(0, text.length - extra.length);
		
		select.append('<option value="' + value + '" selected>' + value + (text && text != value ? ' - ' + text : '') + extra + '</option>');
	}
}

function createPreviousAndNextHtmlEditores(html_elm) {
	var textareas = html_elm.find(".previous_html, .next_html").children("textarea.html");

	$.each(textareas, function(idx, textarea) {
		createObjectItemCodeEditor(textarea, "html", saveGetHtmlContents);
	});
}

function addWordPressBlock(elm) {
	elm = $(elm);
	var block_type_elm = elm.parent();
	var select = block_type_elm.children("select");
	var block_type = select.val();
	
	if (block_type) {
		var block_settings = block_type_elm.parent();
		var wordpress_blocks = block_settings.children(".wordpress_blocks");
		var block_label = select.children("option:selected").text();
		
		var idx = getListNewIndex(wordpress_blocks);
		var html = getWordPressBlockHtml(block_label, block_type, idx);
		
		html = $(html);
		
		//populate select fields with right values
		var wordpress_installation_name = block_settings.find(" > .wordpress_installation_name > select").val();
		prepareHtmlWithWordPressDBDriverData(html, wordpress_installation_data[wordpress_installation_name]);
		
		wordpress_blocks.children(".no_wordpress_blocks").hide();
		wordpress_blocks.append(html);
		
		//create editor for previous_html and next_html
		if (block_type != "code")
			createPreviousAndNextHtmlEditores(html);
		else
			createObjectItemCodeEditor(html.find(".code textarea.php")[0], "php", saveGetHtmlContents);
		
		//set scroll to new html element
		$(window).scrollTop( html.offset().top );
		
		return html;
	}
}

function getWordPressBlockHtml(block_label, block_type, idx) {
	var html = ''
		+ '<li class="wordpress_block_' + block_type + '">'
		+ '	<div class="block_header">'
		+ '		<label>Block: ' + block_label + '</label>'
		+ '		<span class="icon move_up" onClick="moveUpWordPressBlock(this)"></span>'
		+ '		<span class="icon move_down" onClick="moveDownWordPressBlock(this)"></span>'
		+ '		<span class="icon delete" onClick="removeWordPressBlock(this)"></span>'
		+ '	</div>'
		+ '	<div class="block_body">'
		+ block_type_input_html.replace(/#block_type#/g, block_type)
		+ (block_type != "code" ? block_type_previous_html : "");
	
	switch (block_type) {
		case "full_page_html": html += block_type_full_page_html; break;
		case "header": html += block_type_header_html; break;
		case "footer": html += block_type_footer_html; break;
		case "content": html += block_type_content_html; break;
		case "post_comments": html += block_type_post_comments_html; break;
		case "widget": html += block_type_widget_html; break;
		case "side_bar": html += block_type_side_bar_html; break;
		case "menu": html += block_type_menu_html; break;
		case "menu_location": html += block_type_menu_location_html; break;
		case "code": html += block_type_code_html; break;
	}
	
	html += (block_type != "code" ? block_type_next_html : "")
		+ '	</div>'
		+ '</li>';
	
	html = html.replace(/#idx#/g, idx);
	
	return html;
}

function moveUpWordPressBlock(elm) {
	var item = $(elm).parent().closest("li");
	var prev_item = item.prev(":not(.no_wordpress_blocks)");
	
	if (prev_item[0]) {
		var p = item.parent();
		p[0].insertBefore(item[0], prev_item[0]);
		
		switchListChildrenIndexes(p, item, prev_item);
		
		//set scroll to new html element
		$(window).scrollTop( item.offset().top );
	}
}

function moveDownWordPressBlock(elm) {
	var item = $(elm).parent().closest("li");
	var next_item = item.next();
	
	if (next_item[0]) {
		var p = item.parent();
		p[0].insertBefore(next_item[0], item[0]);
		
		switchListChildrenIndexes(p, item, next_item);
		
		//set scroll to new html element
		$(window).scrollTop( item.offset().top );
	}
}

function removeWordPressBlock(elm) {
	if (confirm("Do you wish to remove this wordpress block?")) {
		var li = $(elm).parent().closest("li");
		var p = li.parent();
		li.remove();
		
		if (li.children(":not(.no_wordpress_blocks)").length == 0)
			li.children(".no_wordpress_blocks").show();
	}
}

function onChangePageType(elm) {
	elm = $(elm);
	var posts_type = elm.val();
	var p = elm.parent().parent();
	
	p.children(".page_type_section").hide();
	
	if (posts_type) {
		var posts_type_elm = p.children("." + posts_type);
		var posts_type_input = posts_type_elm.children("input, select").first();
		posts_type_elm.show();
		
		if (posts_type_input.val())
			onChangePageTypeElement(posts_type_input[0]);
	}
	else
		p.find(" > .path > input").val("");
}

function onChangePageTypeElement(elm) {
	elm = $(elm);
	var value = elm.val();
	var p = elm.parent().parent();
	var posts_type = p.find(" > .page_type > select").val();
	
	switch (posts_type) {
		//case "page": value = value; break;
		case "posts_by_category": value = "category/" + value; break;
		case "posts_by_tag": value = "tag/" + value; break;
		case "posts_by_date": value = value.replace(/-/g, "/"); break;
		case "posts_by_existent_date": value = value.replace(/-/g, "/"); break;
		//case "posts_by_id": value = value; break; //post_id is the post_slug
	}
	
	p.find(" > .path > input").val(value);
}

function onChangeThemeType(elm) {
	elm = $(elm);
	var theme_type = elm.val();
	var p = elm.parent().parent();
	
	if (theme_type == "wordpress_theme")
		p.children(".wordpress_theme").show();
	else
		p.children(".wordpress_theme").hide();
}

function onChangeHtmlType(elm) {
	elm = $(elm);
	var html_type = elm.val();
	var p = elm.parent().parent();
	var items = p.children(".convert_html_into_inner_html, .exclude_theme_side_bars, .exclude_theme_menus, .exclude_theme_comments");
	
	if (!html_type)
		items.show();
	else {
		items.hide();
		
		if ($.inArray(html_type, ["only_content_parents", "only_content_parents_and_css", "only_content_parents_and_js", "only_content_parents_and_css_and_js"]) != -1)
			p.children(".convert_html_into_inner_html").show();
	}
}

function onChangePostCommentsType(elm) {
	elm = $(elm);
	var get_directly_from_theme = elm.parent().parent().children(".get_directly_from_theme");
	
	if (elm.val() == "pretty")
		get_directly_from_theme.show();
	else
		get_directly_from_theme.hide();
}

function onChangeWordPressWidget(elm, default_props) {
	elm = $(elm);
	var widget_id = elm.val();
	var widget_options_elm = elm.parent().parent().children(".widget_options");
	var block_settings = elm.parent().closest(".module_get_html_contents_settings");
	var wordpress_installation_name = block_settings.find(" > .wordpress_installation_name > select").val();
	
	if (widget_id) {
		$.ajax({
			type: "post",
			url: call_module_file_prefix_url.replace("#module_file_path#", "get_data") + "&wordpress_installation_name=" + wordpress_installation_name + "&action=get_widget_options&widget_id=" + widget_id,
			data: {widget_options: default_props},
			dataType: "text",
			success: function(data) {
				var json_data = data && ("" + data).substr(0, 1) == "{" ? JSON.parse(data) : null;
				
				if (!json_data) {
					StatusMessageHandler.showError("Error trying to load wordpress widget options.\nPlease try again..." + (json_data ? json_data : ""));
					
					json_data = {widget_options: ""};
				}
				
				var widget_options_html = json_data["widget_options"];
				prepareHtmlWithWordPressWidgetOptionsData(widget_options_elm, widget_options_html);
			},
			error: function() {
				StatusMessageHandler.showError("Error trying to load wordpress widget options.\nPlease try again...");
			},
		});
	}
}

function prepareHtmlWithWordPressWidgetOptionsData(widget_options_elm, widget_options_html) {
	var html = '<label>Widget Options:</label>' + (widget_options_html ? widget_options_html : '<div class="no_widget_options">No options</div>');
	
	//prepare widget_options_html, removing input submit or buttons and change the input names with the right block names
	if (html) {
		html = $(html);
		
		//removing input submit or buttons
		html.find("input[type=submit], button").remove();
		
		//change the input names with the right block names
		var prefix_name = widget_options_elm.parent().closest(".wordpress_block_widget").find(" > .block_body > input[type=hidden].module_settings_property").first().attr("name");
		prefix_name = prefix_name.substr(0, prefix_name.length - "[block_type]".length) + "[widget_options]";
		
		$.each( html.find("input, select, textarea"), function(idx, input) {
			input = $(input);
			var input_name = input.attr("name");
			var pos = input_name.indexOf("[");
			
			if (pos != -1)
				input_name = prefix_name + (pos === 0 ? "" : "[" + input_name.substr(0, pos) + "]") + input_name.substr(pos);
			else
				input_name = prefix_name + "[" + input_name + "]";
			
			input.attr("name", input_name).addClass("module_settings_property");
		});
	}
	
	widget_options_elm.html(html);
}

function getWordPressInstallationName(block_settings) {
	var wordpress_installation_name = block_settings.find(" > .wordpress_installation_name select").val();
	
	if (!wordpress_installation_name)
		wordpress_installation_name = default_wordpress_installation_name;
	
	return wordpress_installation_name;
}

function setPathWordPressInstallationName(block_settings) {
	var wordpress_installation_name = getWordPressInstallationName(block_settings);
	var wordpress_installation_url = wordpress_url_prefix.replace("#installation_name#", wordpress_installation_name);
	
	block_settings.find(" > .path > .wordpress_installation_url").html(wordpress_installation_url);
}

function onChangeWordPressDBDriver(elm) {
	elm = $(elm);
	var wordpress_installation_name = elm.val();
	var block_settings = elm.parent().closest(".module_get_html_contents_settings");
	
	setPathWordPressInstallationName(block_settings, wordpress_installation_name);
	
	if (wordpress_installation_data.hasOwnProperty(wordpress_installation_name)) {
		prepareHtmlWithWordPressDBDriverData(block_settings, wordpress_installation_data[wordpress_installation_name]);
		prepareJSCodeWithWordPressUrl(block_settings, wordpress_installation_data[wordpress_installation_name]["site_url"]);
	}
	else
		$.ajax({
			url: call_module_file_prefix_url.replace("#module_file_path#", "get_data") + "&wordpress_installation_name=" + wordpress_installation_name,
			dataType: "text",
			success: function(data) {
				var json_data = data && ("" + data).substr(0, 1) == "{" ? JSON.parse(data) : null;
				
				if (!json_data) {
					StatusMessageHandler.showError("Error trying to load wordpress db driver data.\nPlease try again..." + (json_data ? json_data : ""));
					
					json_data = {
						pages : {},
						categories : {},
						tags : {},
						posts : {},
						widgets : {},
						side_bars : {},
						menus : {},
						menu_locations : {},
						site_url : "",
					};
				}
				
				var is_first_load = $.isEmptyObject(wordpress_installation_data);
				wordpress_installation_data[wordpress_installation_name] = json_data;
				prepareHtmlWithWordPressDBDriverData(block_settings, json_data);
				
				if (!is_first_load || is_default_values_loaded) //only if is not the first load, bc we don't want to change the loaded task_values
					prepareJSCodeWithWordPressUrl(block_settings, json_data["site_url"]);
			},
			error: function() {
				StatusMessageHandler.showError("Error trying to load wordpress db driver data.\nPlease try again...");
			},
		});
}

function prepareHtmlWithWordPressDBDriverData(html_elm, data) {
	if ($.isPlainObject(data)) {
		//prepare html ui with db driver wordpress data
		$.each(data, function (key, items) {
			if (key != "site_url") {
				var options = "<option></option>";
				var selects = null;
				
				if ($.isPlainObject(items) || $.isArray(items))
					$.each(items, function(item_key, item_value) {
						options += '<option value="' + item_key + '">' + item_value + '</option>';
					});
				
				if (html_elm.is(".module_get_html_contents_settings")) {
					switch (key) {
						case "themes": 
							selects = html_elm.find(" > .wordpress_theme select"); 
							options = options.replace("<option></option>", '<option value="">-- Current Active Theme --</option>');
							break;
						case "pages": selects = html_elm.find(" > .page select"); break;
						case "categories": selects = html_elm.find(" > .posts_by_category select"); break;
						case "tags": selects = html_elm.find(" > .posts_by_tag select"); break;
						case "dates": selects = html_elm.find(" > .posts_by_existent_date select"); break;
						case "posts": selects = html_elm.find(" > .posts_by_id select"); break;
						case "widgets": selects = html_elm.find(" > .wordpress_blocks .widget select"); break;
						case "side_bars": selects = html_elm.find(" > .wordpress_blocks .side_bar select"); break;
						case "menus": selects = html_elm.find(" > .wordpress_blocks .menu select"); break;
						case "menu_locations": selects = html_elm.find(" > .wordpress_blocks .menu_location select"); break;
					}
				}
				else {
					switch (key) {
						case "widgets": selects = html_elm.find(".widget select"); break;
						case "side_bars": selects = html_elm.find(".side_bar select"); break;
						case "menus": selects = html_elm.find(".menu select"); break;
						case "menu_locations": selects = html_elm.find(".menu_location select"); break;
					}
				}
				
				if (selects)
					$.each(selects, function(idx, select) {
						select = $(select);
						var value = select.val();
						var text = select.find("option:selected").text();
						
						select.html(options);
						
						setGetHtmlContentsSelectFieldValue(select, value, text);
					});
			}
		});
	}
}

function prepareJSCodeWithWordPressUrl(block_settings, site_url) {
	var next_html = block_settings.children(".next_html");
	var editor = next_html.data("editor");
	var textarea = next_html.children("textarea.html");
	var html = editor ? editor.getValue() : textarea.val();
	
	if (html) {
		var match = /(var\s+from_url\s*=\s*)[^;]+;/i.exec(html);
		
		if (match && match[0]) {
			html = html.replace(match[0], "var from_url = '" + site_url + "';");
			
			if (editor)
				editor.setValue(html, -1);
			else
				textarea.val(html);
		}
	}
}

function addCodeFunction(elm) {
	elm = $(elm);
	var code_to_add = elm.parent().children("select").val();
	
	if (code_to_add)
		addCodeToEditor(elm.parent().closest(".code"), code_to_add + ";");
}

function addCodeToEditor(code_elm, code_to_add) {
	if (code_to_add) {
		var editor = code_elm.data("editor");
		
		if (editor) {
			editor.focus();
			var cursor_position = editor.getCursorPosition(); // {row: 3, column: 18}
			editor.getSession().insert(cursor_position, code_to_add); // Insert text (second argument) with given position
		}
		else {
			var textarea = code_elm.children("textarea.html");
			textarea.val(textarea.val() + "\n" + code_to_add);
		}
	}
}

function openWordPressPermalinksSettingsPopup(elm) {
	elm = $(elm);
	var block_settings = elm.parent().closest(".module_get_html_contents_settings");
	var wordpress_installation_name = getWordPressInstallationName(block_settings);
	var wordpress_permalinks_settings = block_settings.children(".wordpress_permalinks_settings");
	
	MyFancyPopup.init({
		elementToShow: wordpress_permalinks_settings,
		parentElement: document,
		type: "iframe",
		url: wordpress_installation_admin_login_url_prefix.replace("#installation_name#", wordpress_installation_name),
	});
	MyFancyPopup.showPopup();
}

function openWordPressFunctionsListPopup(elm) {
	var code_elm = $(elm).parent().closest(".code");
	var wordpress_functions = code_elm.parent().closest(".module_get_html_contents_settings").children(".wordpress_functions");
	
	//remove iframe otherwise it will set the on load iframe multiple times...
	wordpress_functions.children("iframe").remove();
	
	MyFancyPopup.init({
		elementToShow: wordpress_functions,
		parentElement: document,
		type: "iframe",
		url: call_module_file_prefix_url.replace("#module_file_path#", "wordpress_functions"),
		onIframeOnLoad: function(originalEvent, iframe) {
			$(iframe).contents().find("table a").each(function(idx, a) {
				a = $(a);
				a.attr("data-href", a.attr("href"));
				a.attr("href", "javascript:void(0)");
				a.on("click", function(e) {
					if (e.ctrlKey)
						window.open( a.attr("data-href"), "wordpress_functions");
					else {
						var code_to_add = $(a).text();
						
						if (code_to_add) {
							addCodeToEditor(code_elm, code_to_add + "(/* Check if this functions receives any arguments */);");
							MyFancyPopup.hidePopup();
						}
					}
					
					return false;
				});
			});
		},
	});
	MyFancyPopup.showPopup();
}

function addWordPressUrlToExcludeConversion(elm) {
	elm = $(elm);
	var p = elm.parent().closest(".allowed_wordpress_urls");
	var ul = p.children("ul");
	
	var html =  '<li>'
			+ '	<input type="text" class="module_settings_property" name="allowed_wordpress_urls[]" />'
			+ '	<span class="icon delete" onClick="removeWordPressUrlToExcludeConversion(this)">Remove</span>'
			+ '</li>';
	
	html = $(html);
	ul.append(html);
	ul.children(".no_urls").hide();
	
	return html;
}

function removeWordPressUrlToExcludeConversion(elm) {
	if (confirm("Do you wish to remove this url?")) {
		var li = $(elm).parent().closest("li");
		var p = li.parent();
		li.remove();
		
		if (li.children(":not(.no_urls)").length == 0)
			li.children(".no_urls").show();
	}
}

function onCheckParseWordPressUrls(elm) {
	elm = $(elm);
	var parse_wordpress_relative_urls = elm.parent().parent().find(".parse_wordpress_relative_urls");
	
	if (elm.is(":checked"))
		parse_wordpress_relative_urls.show();
	else
		parse_wordpress_relative_urls.hide();
}

function saveGetHtmlContents(button) {
	//get previous_html and next_html editors, and getValue and set value to textarea.module_settings_property
	var block_settings = $(".block_obj > .module_settings > .settings > .module_get_html_contents_settings");
	var html_elms = block_settings.find(".previous_html, .next_html, .code");
	
	$.each(html_elms, function(idx, html_elm) {
		html_elm = $(html_elm);
		var editor = html_elm.data("editor");
		var value = "";
		var textarea = html_elm.children("textarea.module_settings_property");
		
		if (editor)
			value = editor.getValue(value);
		else 
			value = html_elm.children("textarea.html").val();
		
		//if code contain a php code, force it to be a string
		if (html_elm.hasClass("code") && value) {
			var v = ("" + value).replace(/^\s+/g, "").replace(/\s+$/g, ""); //trim value
			
			if (v.substr(0, 2) == '<?' && v.substr(v.length - 2) == '?>')
				textarea.attr("value_type", "string");
		}
		
		textarea.val(value);
	});
	
	saveObjectBlock(button, "module_get_html_contents_settings");
	
	//remove value_type in case the code above added it
	block_settings.find(".code textarea.module_settings_property").removeAttr("value_type");
}


