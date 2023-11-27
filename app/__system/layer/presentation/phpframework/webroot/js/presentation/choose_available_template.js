var MyFancyPopupAvailableTemplate = new MyFancyPopupClass();
var MyFancyPopupInstallTemplate = new MyFancyPopupClass();
var MyFancyPopupAvailableTemplateDemo = new MyFancyPopupClass();
var default_available_templates = ["empty", "ajax", "blank", "default"];

function chooseAvailableTemplate(select, options) {
	options = options ? options : {};
	
	var popup = $(".choose_available_template_popup");
	var on_open_func = null;
	var project_id = options.selected_project_id ? options.selected_project_id : selected_project_id;
	var folder_to_filter = options.folder_to_filter ? options.folder_to_filter : "";
	
	if (!popup[0]) {
		var html = '<div class="myfancypopup with_title choose_available_template_popup">'
					+ '<label class="title">Choose a Template</label>'
					+ '<div class="content"></div>'
				+ '</div>';
		
		popup = $(html);
		$(document.body).append(popup);
		
		on_open_func = options["show_templates_only"] ? prepareChooseAvailableTemplateMainProjectHtml : prepareChooseAvailableTemplateTypeHtml;
	}
	
	if (!MyFancyPopupAvailableTemplate.settings) //only init if not inited before
		MyFancyPopupAvailableTemplate.init({
			//options vars
			include_template_samples: options["include_template_samples"] ? options["include_template_samples"] : null,
			include_template_samples_in_regions: options["include_template_samples_in_regions"] == "all" ? "all" : "empty",
			onSelect: options["on_select"] ? options["on_select"] : null,
			onSelectFromOtherProject: options["on_select_from_other_project"] ? options["on_select_from_other_project"] : null,
			available_projects_props: options["available_projects_props"] ? options["available_projects_props"] : null,
			available_projects_templates_props: options["available_projects_templates_props"] ? options["available_projects_templates_props"] : null,
			get_available_templates_props_url: options["get_available_templates_props_url"] ? options["get_available_templates_props_url"] : null,
			install_template_url: options["install_template_url"] ? options["install_template_url"] : null,
			onInstall: options["on_install"] ? options["on_install"] : null,
			hide_choose_different_editor: options["hide_choose_different_editor"] ? true : false,
			hide_choose_different_project: options["hide_choose_different_project"] ? true : false,
			
			//internal vars
			elementToShow: popup,
			parentElement: document,
			onOpen: function() {
				if (typeof on_open_func == "function")
					on_open_func(project_id, folder_to_filter); //only execute once - the first time.
			},
			targetField: select,
			default_project_id: project_id,
		});
	else
		MyFancyPopupAvailableTemplate.settings.onOpen = null; //simply open the popup where the user left, instead of loading the on_open_func handler. 
	
	MyFancyPopupAvailableTemplate.showPopup();
}

function installTemplatePopup(project_id, folder_to_filter) {
	var url = MyFancyPopupAvailableTemplate.settings.install_template_url;
	
	if (url) {
		url += (url.indexOf("?") != -1 ? "&" : "?") + "popup=1&on_success_js_func=MyFancyPopupInstallTemplate.hidePopup";
		
		var popup = $(".install_template_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup with_iframe_title install_template_popup"></div>');
			$(document.body).append(popup);
		}
		
		popup.html('<iframe src="' + url + '"></iframe>');
		
		if (!MyFancyPopupInstallTemplate.settings) //only init if not inited before
			MyFancyPopupInstallTemplate.init({
				elementToShow: popup,
				parentElement: document,
				onClose: function() {
					//reset templates
					MyFancyPopupAvailableTemplate.settings.available_projects_templates_props[project_id] = null;
					
					//reload templates
					loadAvailableProjectTemplatesHtml(project_id, function(proj_id, server_data_fetched) {
						prepareChooseAvailableTemplateDefaultHtml(project_id);
						prepareChooseAvailableTemplateInstalledHtml(project_id, folder_to_filter);
						
						if (server_data_fetched && typeof MyFancyPopupAvailableTemplate.settings.onInstall == "function")
							MyFancyPopupAvailableTemplate.settings.onInstall(project_id, MyFancyPopupAvailableTemplate.settings.available_projects_templates_props[project_id]);
					});
				},
			});
	
		MyFancyPopupInstallTemplate.showPopup();
	}
	else
		alert("install_template_url is undefined. Please contact the sysadmin!");
}

function prepareChooseAvailableTemplateTypeHtml(project_id, folder_to_filter) {
	var popup = $(".choose_available_template_popup");
	popup.children(".back, .choose_different_editor, .install_template").remove();
	
	var html = '<div class="choose_page_workspace">'
				+ '<div class="title">How do you want to build your page?</div>'
				+ '<div class="html_editor">'
					+ '<div class="title">Canvas Editor</div>'
					+ '<div class="description">Create static pages from scratch by designing your HTML by drag&drop.<br/>You can also create dynamic pages.<br/>Recommended for all web-designers...</div>'
					+ '<button onClick="selectAvailableTemplate(\'' + project_id + '\', \'blank\')">Empty Canvas</button>'
				+ '</div>'
				+ '<div class="template_editor">'
					+ '<div class="title">Choose Template</div>'
					+ '<div class="description">Accelerate your design process by starting with a customizable template and dynamic pages.<br/>Recommended for all non-web-designers...</div>'
					+ '<button onClick="prepareChooseAvailableTemplateMainProjectHtml(\'' + project_id + '\', \'' + folder_to_filter + '\')">Browse Templates</button>'
				+ '</div>'
			+ '</div>';
	
	popup.children(".content").html(html).scrollTop(0);
	popup.children(".loading_templates").hide();
	
	MyFancyPopupAvailableTemplate.updatePopup();
}

function prepareChooseAvailableTemplateMainProjectHtml(project_id, folder_to_filter) {
	var popup = $(".choose_available_template_popup");
	var popup_content = popup.children(".content");
	var include_template_samples = MyFancyPopupAvailableTemplate.settings.include_template_samples;
	var include_template_samples_in_regions = MyFancyPopupAvailableTemplate.settings.include_template_samples_in_regions;
	
	if (!MyFancyPopupAvailableTemplate.settings.hide_choose_different_editor && !popup.children(".choose_different_editor")[0])
		popup_content.before('<button class="choose_different_editor" onClick="prepareChooseAvailableTemplateTypeHtml(\'' + project_id + '\', \'' + folder_to_filter + '\');"><i class="icon palette"></i> Choose different editor</button>');
	
	if (MyFancyPopupAvailableTemplate.settings.install_template_url && !popup.children(".install_template")[0])
		popup_content.before('<button class="install_template" onClick="installTemplatePopup(\'' + project_id + '\', \'' + folder_to_filter + '\')">Import Template</button>');
	
	var html = '<div class="default_templates">'
				+ '<div class="title">Default Templates:</div>'
				+ '<ul>'
					+ '<li class="loading_templates"><span class="icon loading"></span> Loading templates...</li>'
				+ '</ul>'
			+ '</div>'
			+ '<div class="installed_templates">'
				+ '<div class="title">Installed Templates:</div>'
				+ '<div class="current_template_folder"></div>'
				+ '<ul>'
					+ '<li class="loading_templates"><span class="icon loading"></span> Loading templates...</li>'
				+ '</ul>'
			+ '</div>';
	
	if (!MyFancyPopupAvailableTemplate.settings.hide_choose_different_project)
		html += '<div class="projects_templates">'
				+ '<div class="title">Other Projects\' Templates:</div>'
				+ '<div class="current_template_folder"></div>'
				+ '<ul>'
					+ '<li class="loading_templates"><span class="icon loading"></span> Loading projects...</li>'
				+ '</ul>'
			+ '</div>';
	
	html += '<div class="options">'
				+ '<div class="include_template_samples">'
					+ '<input type="checkbox" ' + (include_template_samples ? " checked" : "") + ' onChange="onChangeIncludeTemplateSamples(this)"/>'
					+ '<label>Include samples from selected template?</label>'
					+ '<select onChange="onChangeIncludeTemplateSamplesInRegions(this)">'
						+ '<option value="empty"' + (include_template_samples_in_regions == "empty" ? " selected" : "") + '>Only on empty regions</option>'
						+ '<option value="all"' + (include_template_samples_in_regions == "all" ? " selected" : "") + '>In all regions</option>'
					+ '</select>'
					+ '<span class="info" title="Each template comes with samples for each region. If this option is active, the system will add these code to the correspondent regions of the selected template."><i class="icon info"></i></span>'
				+ '</div>'
			+ '</div>'
	
	popup_content.html(html).scrollTop(0);
	
	//load templates
	prepareChooseAvailableTemplateDefaultHtml(project_id);
	prepareChooseAvailableTemplateInstalledHtml(project_id, folder_to_filter);
	
	if (!MyFancyPopupAvailableTemplate.settings.hide_choose_different_project)
		prepareChooseAvailableTemplateProjectsHtml();
}

function prepareChooseAvailableTemplateDefaultHtml(project_id) {
	//prepare default templates
	var aptp = MyFancyPopupAvailableTemplate.settings.available_projects_templates_props;
	var items = $.isPlainObject(aptp) && $.isPlainObject(aptp[project_id]) ? aptp[project_id] : {};
	var ats = getAvailableFilesPropsConvertedWithFolders(items, "", true);
	
	//build default templates html
	var html = '<li class="project_default_template default_template" onclick="selectAvailableTemplate(\'' + project_id + '\', \'\')">'
			+ '<div class="photo_default">' /*+ 'Default'*/ + '</div>'
			+ '<label>Project default template</label>'
		+ '</li>';
	
	for (var i = 0; i < default_available_templates.length; i++) {
		var template_id = default_available_templates[i] + ".php";
		
		if (ats.hasOwnProperty(template_id))
			html += getChooseAvailableTemplateHtml(project_id, "", template_id, ats[template_id]);
	}
	
	//add html
	var popup = $(".choose_available_template_popup");
	var ul = popup.find(" > .content > .default_templates > ul");
	ul.html(html);
}

function prepareChooseAvailableTemplateInstalledHtml(project_id, folder_to_filter) {
	prepareChooseAvailableTemplatesHtml(project_id, folder_to_filter);
}

function prepareChooseAvailableTemplateProjectsHtml(folder_to_filter) {
	var popup = $(".choose_available_template_popup");
	var ul = popup.find(" > .content > .projects_templates > ul");
	ul.parent().children(".back").remove();
	
	var html = '';
	
	if (MyFancyPopupAvailableTemplate.settings.available_projects_props) {
		var available_projects_props = assignObjectRecursively({}, MyFancyPopupAvailableTemplate.settings.available_projects_props);
		delete available_projects_props[ MyFancyPopupAvailableTemplate.settings.default_project_id ]; //remove current project
		//console.log(available_projects_props);
		
		var aps = getAvailableFilesPropsConvertedWithFolders(available_projects_props, folder_to_filter, false);
		//console.log(aps);
		
		if (folder_to_filter) {
			folder_to_filter = folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "");
			var dirs = folder_to_filter.split("/");
			dirs.pop();
			var parent_folder = dirs.join("/");
			
			ul.before('<div class="back" onClick="prepareChooseAvailableTemplateProjectsHtml(\'' + parent_folder + '\');"><i class="icon go_up"></i> Go to parent folder</div>');
		}
		
		if (!$.isEmptyObject(aps)) {
			//add files 
			for (var k in aps) 
				if (aps[k])
					html += getChooseAvailableTemplateProjectHtml(folder_to_filter, k, aps[k]);
		}
	}
	
	if (html == "")
		html = '<li class="empty">There are no available projects...</li>';
	
	ul.html(html);
	
	var info = folder_to_filter ? '<span class="path_parts" onClick="prepareChooseAvailableTemplateProjectsHtml(\'\')">projects</span> ' + getChooseAvailableTemplateCurrentProjectHtml(folder_to_filter, false, false) : '';
	ul.parent().children(".current_template_folder").html(info);
	
	MyFancyPopupAvailableTemplate.updatePopup();
}

function prepareChooseAvailableTemplatesHtml(project_id, folder_to_filter) {
	var aptp = MyFancyPopupAvailableTemplate.settings.available_projects_templates_props;
	var items = $.isPlainObject(aptp) && $.isPlainObject(aptp[project_id]) ? aptp[project_id] : {};
	var is_external_project = project_id && project_id != MyFancyPopupAvailableTemplate.settings.default_project_id;
	var ats = getAvailableFilesPropsConvertedWithFolders(items, folder_to_filter, true);
	
	var popup = $(".choose_available_template_popup");
	var ul = popup.find(" > .content > " + (is_external_project ? ".projects_templates" : ".installed_templates") + " > ul");
	ul.parent().children(".back").remove();
	
	if (folder_to_filter) {
		folder_to_filter = folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "");
		var dirs = folder_to_filter.split("/");
		dirs.pop();
		var parent_folder = dirs.join("/");
		
		ul.before('<div class="back" onClick="prepareChooseAvailableTemplatesHtml(\'' + project_id + '\', \'' + parent_folder + '\');"><i class="icon go_up"></i> Go to parent folder</div>');
	}
	else if (is_external_project)
		ul.before('<div class="back back_to_type" onClick="prepareChooseAvailableTemplateProjectsHtml(\'\');"><i class="icon go_up"></i> Go back to projects</div>');
	
	var html = '';
	
	if (!$.isEmptyObject(ats)) {
		//remove default templates
		var exlude_default_templates = !folder_to_filter && !is_external_project;
		
		//add folders
		/*var folders_exists = false;
		
		for (var k in ats) 
			if (ats[k]) { 
				var is_file = $.isPlainObject(ats[k]) && ats[k]["is_file"] === true;
				
				if (!is_file) {
					html += getChooseAvailableTemplateHtml(project_id, folder_to_filter, k, ats[k]);
					folders_exists = true;
					ats[k] = null;
				}
			}
		
		if (folders_exists)
			html += '<li class="separator"></li>';
		*/
		//add files 
		for (var k in ats) 
			if (ats[k] && (!exlude_default_templates || !ats[k]["is_default_template"]))
				html += getChooseAvailableTemplateHtml(project_id, folder_to_filter, k, ats[k]);
	}
	
	if (html == "") {
		html += '<li class="empty">There are no available templates...';
		
		//if (MyFancyPopupAvailableTemplate.settings.install_template_url && !is_external_project)
		//	html += '<br/>Please install new templates by clicking <a href="javascript:void(0)" onClick="installTemplatePopup(\'' + project_id + '\', \'' + folder_to_filter + '\')">here</a>.';
		
		html += '</li>';
	}
	
	ul.html(html);
	
	var info = '';
	info += is_external_project ? '<span class="path_parts" onClick="prepareChooseAvailableTemplateProjectsHtml(\'\')">projects</span> ' + getChooseAvailableTemplateCurrentProjectHtml(project_id, true, folder_to_filter) : "";
	info += folder_to_filter ? (
			!is_external_project ? '<span class="path_parts" onClick="prepareChooseAvailableTemplatesHtml(\'' + project_id + '\', \'\')">templates</span> ' : ''
		) + getChooseAvailableTemplateCurrentFolderHtml(project_id, folder_to_filter) : '';
	ul.parent().children(".current_template_folder").html(info);
	
	MyFancyPopupAvailableTemplate.updatePopup();
}

function getChooseAvailableTemplateHtml(project_id, folder_to_filter, fp, template_props) {
	var html = "";
	
	var is_file = $.isPlainObject(template_props) && template_props["is_file"] === true;
	fp = is_file ? fp.substr(0, fp.length - 4) : fp;//remove extension if is file
	var template_id = (folder_to_filter ? folder_to_filter + "/" : "") + fp;
	var label = fp.replace("/_/g", " ");
	label = label.charAt(0).toUpperCase() + label.substr(1, label.length - 1);
	var is_external_project = project_id && project_id != MyFancyPopupAvailableTemplate.settings.default_project_id;
	var is_default_template = !is_external_project ? template_props["is_default_template"] : false;
	
	if (is_file)
		html += '<li class="file' + (is_default_template ? " default_template" : "") + '" onClick="selectAvailableTemplate(\'' + project_id + '\', \'' + template_id + '\')" title="' + label + '">'
	else
		html += '<li class="folder" onClick="prepareChooseAvailableTemplatesHtml(\'' + project_id + '\', \'' + template_id + '\');" title="' + label + '">'
	
	if (is_file) {
		var logo = template_props["logo"];
		var demo = template_props["demo"];
		
		if (logo)
			html += '<div class="image"><img src="' + logo + '" onError="$(this).parent().parent().children(\'.photo\').removeClass(\'hidden\'); $(this).parent().remove();" /></div>';
		
		html += '<div class="photo' + (default_available_templates.indexOf(fp) != -1 ? "_" + fp : "") + (logo ? " hidden" : "") + '">' /*+ (fp == "empty" ? "Blank" : (fp == "default" ? "Default" : ""))*/ + '</div>';
		
		html += '<label>' + label + '</label>';
		
		if (demo)
			html += '<div class="show_demo" onClick="showAvailableTemplateDemo(\'' + demo + '\')" title="View Demo"><span class="icon view"></span></div>';
	}
	else { //if is folder
		html += '<div class="photo"></div>';
		html += '<label>' + label + '</label>';
	}
	
	html += '</li>';
	
	return html;
}

function getChooseAvailableTemplateProjectHtml(folder_to_filter, fp, project_props) {
	var html = "";
	
	var is_project = $.isPlainObject(project_props) && project_props["is_file"] === true;
	var project_id = (folder_to_filter ? folder_to_filter + "/" : "") + fp;
	var project_logo_url = $.isPlainObject(project_props) && project_props["logo"] ? project_props["logo"] : null;
	var label = fp.replace("/_/g", " ");
	label = label.charAt(0).toUpperCase() + label.substr(1, label.length - 1);
	
	if (is_project)
		html += '<li class="project ' + (!folder_to_filter && project_id == "common" ? "project_common" : "") + '" onClick="loadAvailableProjectTemplatesHtml(\'' + project_id + '\');" title="' + label + '">'
	else
		html += '<li class="folder project_folder" onClick="prepareChooseAvailableTemplateProjectsHtml(\'' + project_id + '\');" title="' + label + '">'
	
	if (project_logo_url)
		html += '<div class="image">' + (project_logo_url ? '<img src="' + project_logo_url + '" onError="$(this).parent().removeClass("image").addClass("photo");$(this).remove();" />' : '') + '</div>';
	else
		html += '<div class="photo"></div>';
	
	html += '<label>' + label + '</label>';
	html += '</li>';
	
	return html;
}

function getChooseAvailableTemplateCurrentFolderHtml(project_id, current_path) {
	current_path = current_path.replace(/^\/+/g, "").replace(/\/+$/g, "");
	var dirs = current_path.split("/");
	var html = '';
	var parent_folder = "";
	
	for (var i = 0; i < dirs.length; i++) {
		var dir = dirs[i];
		
		if (dir) {
			parent_folder += (parent_folder ? "/" : "") + dir;
			
			html += '<span class="path_parts" onClick="prepareChooseAvailableTemplatesHtml(\'' + project_id + '\', \'' + parent_folder + '\');">' + dir + '</span>';
		}
	}
	
	return html;
}

function getChooseAvailableTemplateCurrentProjectHtml(current_path, is_project, with_project) {
	current_path = current_path.replace(/^\/+/g, "").replace(/\/+$/g, "");
	var dirs = current_path.split("/");
	var html = '';
	var parent_folder = "";
	
	for (var i = 0; i < dirs.length; i++) {
		var dir = dirs[i];
		
		if (dir) {
			parent_folder += (parent_folder ? "/" : "") + dir;
			var is_part_project = i + 1 == dirs.length && is_project;
			
			html += '<span class="path_parts' + (is_part_project ? ' path_part_project' + (with_project ? ' with_project' : '') : '') + '" onClick="' + (is_part_project ? 'prepareChooseAvailableTemplatesHtml(\'' + parent_folder + '\', \'\');' : 'prepareChooseAvailableTemplateProjectsHtml(\'' + parent_folder + '\');') + '">' + dir + '</span>';
		}
	}
	
	return html;
}

function loadAvailableProjectTemplatesHtml(project_id, handler_func) {
	handler_func = typeof handler_func == "function" ? handler_func : prepareChooseAvailableTemplatesHtml;
	
	if (MyFancyPopupAvailableTemplate.settings.get_available_templates_props_url) {
		if ($.isPlainObject(MyFancyPopupAvailableTemplate.settings.available_projects_templates_props[project_id])) {
			handler_func(project_id);
		}
		else {
			var is_external_project = project_id && project_id != MyFancyPopupAvailableTemplate.settings.default_project_id;
			var popup = $(".choose_available_template_popup");
			var ul = popup.find(" > .content > " + (is_external_project ? ".projects_templates" : ".installed_templates") + " > ul");
			ul.html('<li class="loading_templates"><span class="icon loading"></span> Loading templates...</li>');
			
			var url = MyFancyPopupAvailableTemplate.settings.get_available_templates_props_url.replace(/#path#/, project_id);
			
			$.ajax({
				type : "get",
				url : url,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					MyFancyPopupAvailableTemplate.settings.available_projects_templates_props[project_id] = data;
					
					handler_func(project_id, true);
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					if (jqXHR.responseText)
						StatusMessageHandler.showError(jqXHR.responseText);
				},
			});
		}
	}
}

function selectAvailableTemplate(project_id, selected_template) {
	var select = $(MyFancyPopupAvailableTemplate.settings.targetField);
	var current_template = select.val();
	var is_external_project = project_id && project_id != MyFancyPopupAvailableTemplate.settings.default_project_id;
	
	if (is_external_project) {
		MyFancyPopupAvailableTemplate.hidePopup();
		
		if (typeof MyFancyPopupAvailableTemplate.settings.onSelectFromOtherProject == "function")
			MyFancyPopupAvailableTemplate.settings.onSelectFromOtherProject(selected_template, project_id);
	}
	else if (current_template != selected_template) {
		if (select && select[0]) { //note that select could be null
			select.val(selected_template);
			
			//add template to selet field, if not exists yet, bc it may be a new template recent installed.
			if (select.val() != selected_template) {
				select.append('<option value="' + selected_template + '">' + selected_template + '</option>');
				select.val(selected_template);
			}
			
			select.trigger("change"); //on edit_entity_simple we must trigger the onChangeTemplate method.
		}
		
		if (typeof layer_default_template != "undefined" && selected_template == layer_default_template)
			StatusMessageHandler.showMessage("This template is currently the default template for this project!");
		
		MyFancyPopupAvailableTemplate.hidePopup();
		
		if (typeof MyFancyPopupAvailableTemplate.settings.onSelect == "function")
			MyFancyPopupAvailableTemplate.settings.onSelect(selected_template, {
				include_template_samples: MyFancyPopupAvailableTemplate.settings.include_template_samples,
				include_template_samples_in_regions: MyFancyPopupAvailableTemplate.settings.include_template_samples_in_regions
			});
	}
	else {
		StatusMessageHandler.showMessage("This template is already the current selected template!");
		MyFancyPopupAvailableTemplate.hidePopup();
	}
}

function showAvailableTemplateDemo(demo_url) {
	window.event.stopPropagation(); //prevent the event to fire in the parent "li" html element.
	
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
		url: demo_url,
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
			
			if (dirs.length == 0) {
				var file_id = file_name.replace(/\.php$/, "");
				
				if ($.inArray(file_id, default_available_templates) != -1)
					props["is_default_template"] = true;
			}
			
			obj[file_name] = props;
		}
	}
	
	return ats;
}

function onChangeIncludeTemplateSamples(elm) {
	MyFancyPopupAvailableTemplate.settings.include_template_samples = elm.checked ? true : false;
}

function onChangeIncludeTemplateSamplesInRegions(elm) {
	MyFancyPopupAvailableTemplate.settings.include_template_samples_in_regions = $(elm).val();
}

