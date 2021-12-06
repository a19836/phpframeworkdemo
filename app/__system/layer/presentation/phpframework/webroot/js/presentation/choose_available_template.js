var available_templates_props = {};
var MyFancyPopupAvailableTemplate = new MyFancyPopupClass();

function chooseAvailableTemplate(select, options) {
	var popup = $(".choose_available_template_popup");
	var on_open_func = null;
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup choose_available_template_popup">'
				+ '<label>Choose a Template</label>'
				+ '<div class="install_template">To install a new Template please click <a href="' + install_template_url + '">here</a></div>'
				+ '<div class="current_template_folder"></div>'
				+ '<div class="loading_templates"><span class="icon loading"></span> Loading templates...</div>'
				+ '<ul></ul>'
				+ '</div>');
		$(document.body).append(popup);
		
		on_open_func = options && options["show_templates_only"] ? prepareChooseAvailableTemplateHtml : prepareChooseAvailableTemplateTypeHtml;
	}
	
	MyFancyPopupAvailableTemplate.init({
		elementToShow: popup,
		parentElement: document,
		onOpen: function() {
			if (typeof on_open_func == "function")
				on_open_func(); //only execute once - the first time.
		},
		
		targetField: select,
		onSelect: options && options["on_select"] ? options["on_select"] : null
	});
	MyFancyPopupAvailableTemplate.showPopup();
}

function prepareChooseAvailableTemplateTypeHtml() {
	var url = "" + document.location;
	url = url.replace(/edit_entity_type=[^&]*/g, "");
	url += (url.indexOf("?") != -1 ? "&" : "?") + "edit_entity_type=advanced";
	
	var html = '<li class="template_type html_editor" onClick="document.location=\'' + url + '\';">'
				+ '<label>Free Html Editor</label>'
				+ '<div class="photo"></div>'
			+ '</li>'
			+ '<li class="template_type template_editor" onClick="prepareChooseAvailableTemplateHtml()">'
				+ '<label>With Template Editor</label>'
				+ '<div class="photo"></div>'
			+ '</li>';
	
	var popup = $(".choose_available_template_popup");
	popup.children("ul").html(html);
	popup.children(".loading_templates").hide();
	
	MyFancyPopupAvailableTemplate.updatePopup();
}

function prepareChooseAvailableTemplateHtml(folder_to_filter) {
	var ats = getAvailableTemplatesConvertedWithFolders(folder_to_filter);
	var html = '';
	
	if (folder_to_filter) {
		folder_to_filter = folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "");
		var dirs = folder_to_filter.split("/");
		dirs.pop();
		var parent_folder = dirs.join("/");
		
		html += '<li class="back" onClick="prepareChooseAvailableTemplateHtml(\'' + parent_folder + '\');">Go Up</li>';
	}
	else
		html += '<li class="back back_to_type" onClick="prepareChooseAvailableTemplateTypeHtml();">Choose different Editor</li>';
	
	if (!$.isEmptyObject(ats)) {
		html += '<li class="project_default_template" onClick="selectAvailableTemplate();">'
					+ '<label>Project default template</label>'
					+ '<div class="photo_default" onclick="selectAvailableTemplate()">Default</div>'
				+ '</li>';
		
		//add default templates
		if (!folder_to_filter) {
			var default_available_templates = ["empty", "ajax", "default"];
			
			for (var i = 0; i < default_available_templates.length; i++) {
				var default_available_template = default_available_templates[i] + ".php";
				
				if (ats.hasOwnProperty(default_available_template)) {
					html += getChooseAvailableTemplateHtml(folder_to_filter, default_available_template, ats[default_available_template]);
					
					ats[default_available_template] = null;
				}
			}
			
			//add separator
			if (html != "")
				html += '<li class="separator"></li>';
		}
		
		//add folders
		for (var k in ats) 
			if (ats[k]) { 
				var is_file = $.isPlainObject(ats[k]) && ats[k]["is_file"] === true;
				
				if (!is_file) {
					html += getChooseAvailableTemplateHtml(folder_to_filter, k, ats[k]);
					
					ats[k] = null;
				}
			}
		
		//add files 
		for (var k in ats) 
			if (ats[k])
				html += getChooseAvailableTemplateHtml(folder_to_filter, k, ats[k]);
	}
	else
		html += '<li>There are no available templates...<br/>Please install new templates by clicking <a href="' + install_template_url + '">here</a>.</li>';
		
	var popup = $(".choose_available_template_popup");
	popup.children(".current_template_folder").html(folder_to_filter ? '<span class="icon folder"></span> ' + folder_to_filter + "/" : "");
	popup.children("ul").html(html);
	popup.children(".loading_templates").hide();
	
	MyFancyPopupAvailableTemplate.updatePopup();
}

function getChooseAvailableTemplateHtml(folder_to_filter, fp, template_props) {
	var html = "";
	
	var is_file = $.isPlainObject(template_props) && template_props["is_file"] === true;
	fp = is_file ? fp.substr(0, fp.length - 4) : fp;//remove extension if is file
	var template_id = (folder_to_filter ? folder_to_filter + "/" : "") + fp;
	var label = fp.replace("/_/g", " ");
	label = label.charAt(0).toUpperCase() + label.substr(1, label.length - 1);
	
	if (is_file)
		html += '<li class="file">'
	else
		html += '<li class="folder" onClick="prepareChooseAvailableTemplateHtml(\'' + template_id + '\');">'
	
	html += '<label>' + label + '</label>';
	
	if (is_file) {
		var logo = template_props["logo"];
		var demo = template_props["demo"];
		
		if (logo)
			html += '<img src="' + logo + '" onClick="selectAvailableTemplate(\'' + template_id + '\')" />';
		else 
			html += '<div class="photo' + (fp == "ajax" || fp == "empty" || fp == "default" ? "_" + fp : "") + '" onClick="selectAvailableTemplate(\'' + template_id + '\')">' + (fp == "empty" ? "Blank" : (fp == "default" ? "Default" : "")) + '</div>';
		
		html += '<a class="select_template" href="javascript:void(0)" onClick="selectAvailableTemplate(\'' + template_id + '\')"><span class="icon enable"></span> Select Template</a>';
			
		if (demo)
			html += '<a class="show_demo" href="javascript:void(0)" onClick="showAvailableTemplateDemo(\'' + demo + '\')">View Demo <span class="icon view"></span></a>';
		else
			html += '<span class="show_demo">No demo</span>';
	}
	else //if is folder
		html += '<div class="photo"></div><a href="javascript:void(0)">View Templates in this folder</a>';
	
	html += '</li>';
	
	return html;
}

function selectAvailableTemplate(selected_template) {
	var select = $(MyFancyPopupAvailableTemplate.settings.targetField);
	var current_template = select.val();
	
	if (!selected_template) {
		MyFancyPopupAvailableTemplate.hidePopup();
		
		if (typeof MyFancyPopupAvailableTemplate.settings.onSelect == "function")
			MyFancyPopupAvailableTemplate.settings.onSelect(selected_template);
	}
	else if (current_template != selected_template) {
		select.val(selected_template);
		
		select.trigger("change"); //on edit_entity_simple we must trigger the onChangeTemplate method.
		
		if (typeof layer_default_template != "undefined" && selected_template == layer_default_template)
			StatusMessageHandler.showMessage("This template is currently the default template for this project!");
		
		MyFancyPopupAvailableTemplate.hidePopup();
		
		if (typeof MyFancyPopupAvailableTemplate.settings.onSelect == "function")
			MyFancyPopupAvailableTemplate.settings.onSelect(selected_template);
	}
	else {
		StatusMessageHandler.showMessage("This template is already the current selected template!");
		MyFancyPopupAvailableTemplate.hidePopup();
	}
}

function showAvailableTemplateDemo(demo_url) {
	var MyFancyPopupAvailableTemplateDemo = new MyFancyPopupClass();
	
	var popup = $(".show_available_template_demo_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup show_available_template_demo_popup"><iframe></iframe></div>');
		$(document.body).append(popup);
	}
	else {
		//remove and readd iframe so we don't see the previous loaded html
		popup.children("iframe").remove(); 
		popup.prepend('<iframe></iframe>');
	}
	
	MyFancyPopupAvailableTemplateDemo.init({
		elementToShow: popup,
		parentElement: document,
		type: "iframe",
		url: demo_url
	});
	MyFancyPopupAvailableTemplateDemo.showPopup();
}

function getAvailableTemplatesConvertedWithFolders(folder_to_filter) {
	var ats = {};
	folder_to_filter = folder_to_filter ? folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "") + "/" : "";
	
	for (var fp in available_templates_props) {
		var template_props = available_templates_props[fp];
		
		fp = fp.replace(/[\/]+/, "/"); //remove duplicated "/"
		fp += ".php"; //This avoids the case where there is a file and a folder with the same name. If we do not add ".php", the one of them will be overwriten by the other one.
		
		if (!folder_to_filter || fp.substr(0, folder_to_filter.length) == folder_to_filter) {
			var fp_aux = fp;
			
			if (folder_to_filter)
				fp_aux = fp_aux.substr(folder_to_filter.length);
			
			var dirs = fp_aux.split("/");
			var file_name = dirs.pop();
			var obj = ats;
			
			for (var j = 0; j < dirs.length; j++) {
				var dir = dirs[j];
				
				if (!obj.hasOwnProperty(dir))
					obj[dir] = {};
				
				obj = obj[dir];
			}
			
			template_props["is_file"] = true;
			obj[file_name] = template_props;
		}
	}
	
	return ats;
}

