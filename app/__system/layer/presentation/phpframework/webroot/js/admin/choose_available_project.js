function updateLayerProjects(folder_to_filter) {
	var option = $(".choose_available_project > .layer select option:selected");
	var bean_name = option.attr("bean_name");
	var layer_projects = layers_props && bean_name && layers_props[bean_name] ? layers_props[bean_name]["projects"] : null;
	
	prepareChooseAvailableProjectsHtml(layer_projects, folder_to_filter);
}

function prepareChooseAvailableProjectsHtml(layer_projects, folder_to_filter) {
	var aps = getAvailableProjectsConvertedWithFolders(layer_projects, folder_to_filter);
	var html = '';
	
	if (folder_to_filter) {
		folder_to_filter = folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "");
		var dirs = folder_to_filter.split("/");
		dirs.pop();
		var parent_folder = dirs.join("/");
		
		html += '<li class="back" onClick="updateLayerProjects(\'' + parent_folder + '\');">Go Up</li>';
	}
	
	if (!$.isEmptyObject(aps)) {
		//add folders
		for (var k in aps) 
			if (aps[k]) { 
				var is_project = $.isPlainObject(aps[k]) && aps[k]["is_project"] === true;
				
				if (!is_project) {
					html += getChooseAvailableProjectHtml(folder_to_filter, k, aps[k]);
					
					aps[k] = null;
				}
			}
		
		//add files 
		for (var k in aps) 
			if (aps[k])
				html += getChooseAvailableProjectHtml(folder_to_filter, k, aps[k]);
	}
	else
		html += '<li class="no_projects">There are no available projects...<br/>Please create a new project by clicking <a href="' + create_project_url + '">here</a>.</li>';
	
	var choose_available_project = $(".choose_available_project");
	choose_available_project.children(".current_project_folder").html(folder_to_filter ? '<span class="icon folder"></span> ' + folder_to_filter + "/" : "");
	choose_available_project.children("ul").html(html);
	choose_available_project.children(".loading_projects").hide();
}

function getChooseAvailableProjectHtml(folder_to_filter, fp, project_props) {
	var html = "";
	
	var is_project = $.isPlainObject(project_props) && project_props["is_project"] === true;
	var project_id = (folder_to_filter ? folder_to_filter + "/" : "") + fp;
	var project_logo_url = $.isPlainObject(project_props) && project_props["logo"] ? project_props["logo"] : null;
	var label = fp.replace("/_/g", " ");
	label = label.charAt(0).toUpperCase() + label.substr(1, label.length - 1);
	
	if (is_project)
		html += '<li class="project ' + (!folder_to_filter && project_id == "common" ? "project_common" : "") + '" onClick="selectAvailableProject(\'' + project_id + '\', event);">'
	else
		html += '<li class="folder" onClick="updateLayerProjects(\'' + project_id + '\');">'
	
	html += '	<label>' + label + '</label>';
	
	if (project_logo_url)
		html += '	<div class="image">' + (project_logo_url ? '<img src="' + project_logo_url + '" onError="$(this).parent().removeClass("image").addClass("photo");$(this).remove();" />' : '') + '</div>';
	else
		html += '	<div class="photo"></div>';
	
	if (!is_project) //if is folder
		html += '<a href="javascript:void(0)">View Projects in this folder</a>';
	
	html += '</li>';
	
	return html;
}

function selectAvailableProject(project_id, originalEvent) {
	var option = $(".choose_available_project > .layer select option:selected");
	var bean_name = option.attr("bean_name");
	var bean_file_name = option.attr("bean_file_name");
	var url = select_project_url.replace("#bean_name#", bean_name).replace("#bean_file_name#", bean_file_name).replace("#project#", project_id);
	
	//if ctrl key is pressed
	if (originalEvent && (originalEvent.ctrlKey || originalEvent.keyCode == 65)) {
		var win = window.open(url);
		
		if (win)
			win.focus();
	}
	else if (is_popup) { //if is popup
		if (typeof window.parent.MyFancyPopupProjects != "undefined" && typeof window.parent.MyFancyPopupProjects.settings.goTo == "function")
			window.parent.MyFancyPopupProjects.settings.goTo(url, originalEvent);
		else
			window.parent.document.location = url;
	}
	else //if is current window
		document.location = url;
}

function getAvailableProjectsConvertedWithFolders(layer_projects, folder_to_filter) {
	var aps = {};
	folder_to_filter = folder_to_filter ? folder_to_filter.replace(/[\/]+/, "/").replace(/[\/]+$/, "") + "/" : "";
	
	if (layer_projects)
		for (var fp in layer_projects) {
			var project_props = layer_projects[fp];
			
			fp = fp.replace(/[\/]+/, "/"); //remove duplicated "/"
			
			if (!folder_to_filter || fp.substr(0, folder_to_filter.length) == folder_to_filter) {
				var fp_aux = fp;
				
				if (folder_to_filter)
					fp_aux = fp_aux.substr(folder_to_filter.length);
				
				var dirs = fp_aux.split("/");
				var file_name = dirs.pop();
				var obj = aps;
				
				for (var j = 0; j < dirs.length; j++) {
					var dir = dirs[j];
					
					if (!obj.hasOwnProperty(dir))
						obj[dir] = {};
					
					obj = obj[dir];
				}
				
				project_props["is_project"] = true;
				obj[file_name] = project_props;
			}
		}
	
	return aps;
}

function openAddProjectPopup(url) {
	var popup = $(".add_project_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup add_project_popup"><iframe src="' + url + '"></iframe></div>');
		$(document.body).append(popup);
	}
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		onClose: function() {
			MyFancyPopup.hidePopup();
			MyFancyPopup.showOverlay();
			MyFancyPopup.showLoading();
			
			document.location.reload();
		},
	});
	MyFancyPopup.showPopup();
}

