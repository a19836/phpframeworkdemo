var MyFancyPopupAvailableTemplate = new MyFancyPopupClass();
var MyFancyPopupInstallTemplate = new MyFancyPopupClass();

function chooseAvailableTemplate(select, options) {
	var popup = $(".choose_available_template_popup");
	var on_open_func = null;
	
	options = options ? options : {};
	
	if (!popup[0]) {
		var html = '<div class="myfancypopup choose_available_template_popup">'
					+ '<label>Choose a Template</label>';
		
		if (options["install_template_url"])
			html += 	  '<div class="install_template">To install a new Template please click <a href="javascript:void(0)" onClick="installTemplatePopup()">here</a></div>';
		
		html += 		  '<div class="current_template_folder"></div>'
					+ '<div class="loading_templates"><span class="icon loading"></span> Loading templates...</div>'
					+ '<ul></ul>'
				+ '</div>';
		
		popup = $(html);
		$(document.body).append(popup);
		
		on_open_func = options["show_templates_only"] ? prepareChooseAvailableTemplateHtml : prepareChooseAvailableTemplateTypeHtml;
	}
	
	if (!MyFancyPopupAvailableTemplate.settings) //only init if not inited before
		MyFancyPopupAvailableTemplate.init({
			//options vars
			onSelect: options["on_select"] ? options["on_select"] : null,
			onSelectFromOtherProject: options["on_select_from_other_project"] ? options["on_select_from_other_project"] : null,
			available_projects_props: options["available_projects_props"] ? options["available_projects_props"] : null,
			available_projects_templates_props: options["available_projects_templates_props"] ? options["available_projects_templates_props"] : null,
			get_available_templates_props_url: options["get_available_templates_props_url"] ? options["get_available_templates_props_url"] : null,
			install_template_url: options["install_template_url"] ? options["install_template_url"] : null,
			hide_choose_different_editor: options["hide_choose_different_editor"] ? true : false,
			
			//internal vars
			elementToShow: popup,
			parentElement: document,
			onOpen: function() {
				if (typeof on_open_func == "function") {
					var folder_to_filter = MyFancyPopupAvailableTemplate.settings ? MyFancyPopupAvailableTemplate.settings.folder_to_filter : null;
					
					on_open_func(folder_to_filter); //only execute once - the first time.
				}
			},
			targetField: select,
			choose_template_selected_project_id: selected_project_id,
			folder_to_filter: null,
		});
	
	MyFancyPopupAvailableTemplate.showPopup();
}

function installTemplatePopup() {
	var url = MyFancyPopupAvailableTemplate.settings.install_template_url;
	
	if (url) {
		var popup = $(".install_template_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup install_template_popup"><iframe src="' + url + '"></iframe></div>');
			$(document.body).append(popup);
		}
		
		if (!MyFancyPopupInstallTemplate.settings) //only init if not inited before
			MyFancyPopupInstallTemplate.init({
				elementToShow: popup,
				parentElement: document,
				onClose: function() {
					//backup vars
					var current_project_id = MyFancyPopupAvailableTemplate.settings.choose_template_selected_project_id;
					var current_folder_to_filter = MyFancyPopupAvailableTemplate.settings.folder_to_filter;
					
					//reset templates
					MyFancyPopupAvailableTemplate.settings.available_projects_templates_props = {};
					
					//reload templates
					loadAvailableProjectTemplatesHtml(selected_project_id, function() {
						if (current_project_id && current_project_id != selected_project_id)
							loadAvailableProjectTemplatesHtml(current_project_id, function() {
								prepareChooseAvailableTemplateHtml(current_folder_to_filter);
							});
						else
							prepareChooseAvailableTemplateHtml(current_folder_to_filter);
					});
				},
			});
	
		MyFancyPopupInstallTemplate.showPopup();
	}
	else
		alert("install_template_url is undefined. Please contact the sysadmin!");
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
	var project_id = MyFancyPopupAvailableTemplate.settings.choose_template_selected_project_id;
	var aptp = MyFancyPopupAvailableTemplate.settings.available_projects_templates_props;
	var items = $.isPlainObject(aptp) && $.isPlainObject(aptp[project_id]) ? aptp[project_id] : {};
	var is_external_project = project_id && project_id != selected_project_id;
	
	MyFancyPopupAvailableTemplate.settings.folder_to_filter = folder_to_filter; //save folder_to_filter when we reopen the popup
	
	var ats = getAvailableFilesPropsConvertedWithFolders(items, folder_to_filter, true);
	var html = '';
	
	if (folder_to_filter) {
		folder_to_filter = folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "");
		var dirs = folder_to_filter.split("/");
		dirs.pop();
		var parent_folder = dirs.join("/");
		
		html += '<li class="back" onClick="prepareChooseAvailableTemplateHtml(\'' + parent_folder + '\');">Parent Folder</li>';
	}
	else if (is_external_project)
		html += '<li class="back back_to_type" onClick="prepareChooseAvailableProjectsHtml();">Go Back</li>';
	else {
		if (!MyFancyPopupAvailableTemplate.settings.hide_choose_different_editor)
			html += '<li class="back back_to_type" onClick="prepareChooseAvailableTemplateTypeHtml();">Choose different Editor</li>';
		
		if (MyFancyPopupAvailableTemplate.settings.get_available_templates_props_url)
			html += '<li class="other_project" onClick="prepareChooseAvailableProjectsHtml();">Choose different Project</li>';
	}
	
	if (!$.isEmptyObject(ats)) {
		//add default templates
		if (!folder_to_filter) {
			if (!is_external_project)
				html += '<li class="project_default_template" onClick="selectAvailableTemplate();">'
						+ '<label>Project default template</label>'
						+ '<div class="photo_default" onclick="selectAvailableTemplate()">Default</div>'
					+ '</li>';
			
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
	else {
		html += '<li>There are no available templates...';
		
		if (MyFancyPopupAvailableTemplate.settings.install_template_url)
			html += '<br/>Please install new templates by clicking <a href="javascript:void(0)" onClick="installTemplatePopup()">here</a>.';
		
		html += '</li>';
	}
	
	var popup = $(".choose_available_template_popup");
	popup.children("ul").html(html);
	popup.children(".loading_templates").hide();
	
	var info = '';
	info += is_external_project ? '<span class="icon project"></span> ' + project_id + "/" : "";
	info += folder_to_filter ? '<span class="icon folder' + (is_external_project ? " with_project" : "") + '"></span> ' + folder_to_filter + "/" : '';
	popup.children(".current_template_folder").html(info);
	
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
	
	html += '<label title="' + label + '">' + label + '</label>';
	
	if (is_file) {
		var logo = template_props["logo"];
		var demo = template_props["demo"];
		
		if (logo)
			html += '<img src="' + logo + '" onClick="selectAvailableTemplate(\'' + template_id + '\')" onError="$(this).parent().children(\'.photo\').removeClass(\'hidden\'); $(this).remove();" />';
		
		html += '<div class="photo' + (fp == "ajax" || fp == "empty" || fp == "default" ? "_" + fp : "") + (logo ? " hidden" : "") + '" onClick="selectAvailableTemplate(\'' + template_id + '\')">' + (fp == "empty" ? "Blank" : (fp == "default" ? "Default" : "")) + '</div>';
		
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

function prepareChooseAvailableProjectsHtml(folder_to_filter) {
	var html = '<div class="no_projects">There are no available projects...</div>';
	
	if (MyFancyPopupAvailableTemplate.settings.available_projects_props) {
		var available_projects_props = Object.assign({}, MyFancyPopupAvailableTemplate.settings.available_projects_props);
		delete available_projects_props[selected_project_id]; //remove current project
		//console.log(available_projects_props);
		
		var aps = getAvailableFilesPropsConvertedWithFolders(available_projects_props, folder_to_filter, false);
		//console.log(aps);
		
		if (!$.isEmptyObject(aps)) {
			if (folder_to_filter) {
				folder_to_filter = folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "");
				var dirs = folder_to_filter.split("/");
				dirs.pop();
				var parent_folder = dirs.join("/");
				
				html = '<li class="back" onClick="prepareChooseAvailableProjectsHtml(\'' + parent_folder + '\');">Parent Folder</li>';
			}
			else
				html = '<li class="back" onClick="backToMainProjectAvailableTemplatesHtml()">Go Back</li>';
			
			//add files 
			for (var k in aps) 
				if (aps[k])
					html += getChooseAvailableProjectHtml(folder_to_filter, k, aps[k]);
		}
	}
	
	var popup = $(".choose_available_template_popup");
	popup.children("ul").html(html);
	popup.children(".loading_templates").hide();
	
	MyFancyPopupAvailableTemplate.updatePopup();
}

function backToMainProjectAvailableTemplatesHtml() {
	MyFancyPopupAvailableTemplate.settings.choose_template_selected_project_id = selected_project_id; 
	
	prepareChooseAvailableTemplateHtml('');
}

function getChooseAvailableProjectHtml(folder_to_filter, fp, project_props) {
	var html = "";
	
	var is_project = $.isPlainObject(project_props) && project_props["is_file"] === true;
	var project_id = (folder_to_filter ? folder_to_filter + "/" : "") + fp;
	var project_logo_url = $.isPlainObject(project_props) && project_props["logo"] ? project_props["logo"] : null;
	var label = fp.replace("/_/g", " ");
	label = label.charAt(0).toUpperCase() + label.substr(1, label.length - 1);
	
	if (is_project)
		html += '<li class="project ' + (!folder_to_filter && project_id == "common" ? "project_common" : "") + '" onClick="loadAvailableProjectTemplatesHtml(\'' + project_id + '\');">'
	else
		html += '<li class="folder" onClick="prepareChooseAvailableProjectsHtml(\'' + project_id + '\');">'
	
	html += '	<label title="' + label + '">' + label + '</label>';
	
	if (project_logo_url)
		html += '	<div class="image">' + (project_logo_url ? '<img src="' + project_logo_url + '" onError="$(this).parent().removeClass("image").addClass("photo");$(this).remove();" />' : '') + '</div>';
	else
		html += '	<div class="photo"></div>';
	
	if (!is_project) //if is folder
		html += '<a href="javascript:void(0)">View Projects in this folder</a>';
	
	html += '</li>';
	
	return html;
}

function loadAvailableProjectTemplatesHtml(project_id, handler_func) {
	handler_func = typeof handler_func == "function" ? handler_func : prepareChooseAvailableTemplateHtml;
	
	if (MyFancyPopupAvailableTemplate.settings.get_available_templates_props_url) {
		if ($.isPlainObject(MyFancyPopupAvailableTemplate.settings.available_projects_templates_props[project_id])) {
			MyFancyPopupAvailableTemplate.settings.choose_template_selected_project_id = project_id;
			handler_func();
		}
		else {
			var url = MyFancyPopupAvailableTemplate.settings.get_available_templates_props_url.replace(/#path#/, project_id);
			
			$.ajax({
				type : "get",
				url : url,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					MyFancyPopupAvailableTemplate.settings.choose_template_selected_project_id = project_id;
					MyFancyPopupAvailableTemplate.settings.available_projects_templates_props[project_id] = data;
					
					handler_func();
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					if (jqXHR.responseText)
						StatusMessageHandler.showError(jqXHR.responseText);
				},
			});
		}
	}
}

function selectAvailableTemplate(selected_template) {
	var select = $(MyFancyPopupAvailableTemplate.settings.targetField);
	var current_template = select.val();
	var project_id = MyFancyPopupAvailableTemplate.settings.choose_template_selected_project_id;
	var is_external_project = project_id && project_id != selected_project_id;
	
	if (!selected_template) {
		MyFancyPopupAvailableTemplate.hidePopup();
		
		if (typeof MyFancyPopupAvailableTemplate.settings.onSelect == "function")
			MyFancyPopupAvailableTemplate.settings.onSelect(selected_template);
	}
	else if (is_external_project) {
		MyFancyPopupAvailableTemplate.hidePopup();
		
		if (typeof MyFancyPopupAvailableTemplate.settings.onSelectFromOtherProject == "function")
			MyFancyPopupAvailableTemplate.settings.onSelectFromOtherProject(selected_template, project_id);
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

function getAvailableFilesPropsConvertedWithFolders(available_props, folder_to_filter, add_extension) {
	var ats = {};
	folder_to_filter = folder_to_filter ? folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "") + "/" : "";
	
	for (var fp in available_props) {
		var props = available_props[fp];
		
		fp = fp.replace(/[\/]+/, "/"); //remove duplicated "/"
		fp += add_extension ? ".php" : ""; //This avoids the case where there is a file and a folder with the same name. If we do not add ".php", the one of them will be overwriten by the other one.
		
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
			
			props["is_file"] = true;
			obj[file_name] = props;
		}
	}
	
	return ats;
}

