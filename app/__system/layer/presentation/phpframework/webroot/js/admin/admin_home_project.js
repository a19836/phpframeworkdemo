var pagesFromFileManagerTree = null;
var templatesFromFileManagerTree = null;
var default_available_templates = ["empty", "ajax", "default"];

$(function() {
	var project_files = $(".admin_panel .project_files");
	project_files.tabs({active: active_tab});
	
	pagesFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_selection : false,
		toggle_children_on_click : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : prepareProjectLayerNodes2,
		ajax_callback_error : validateLayerNodesRequest,
		on_select_callback : selectMyTreeNode,
		default_id: "pages_",
	});
	pagesFromFileManagerTree.init("pages");
	
	templatesFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_selection : false,
		toggle_children_on_click : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : prepareProjectLayerNodes2,
		ajax_callback_error : validateLayerNodesRequest,
		on_select_callback : selectMyTreeNode,
		default_id: "templates_",
	});
	templatesFromFileManagerTree.init("templates");
	
	pagesFromFileManagerTree.refreshNodeChilds( project_files.find(".pages > .mytree > li")[0] );
	templatesFromFileManagerTree.refreshNodeChilds( project_files.find(".templates > .mytree > li")[0] );
	
	onClickPagesTab();
	
	$(window).resize(function() {
		MyFancyPopup.updatePopup();
	});
	
	MyFancyPopup.hidePopup();
});

function prepareProjectLayerNodes2(ul, data) {
	prepareLayerNodes2(ul, data);
	
	ul = $(ul);
	
	if (ul.children().length == 0) 
		ul.append('<li class="empty_files">No files availabes...</li>');
	else {
		//only show pages and templates. All the other items should be removed
		ul.find("li").each(function(idx, li) {
			li = $(li);
			var a = li.children("a");
			
			if (!a.children("i").is(".entity_file, .template_file, .folder, .template_folder"))
				li.remove();
			else
				li.attr("title", a.children("label").text());
		});
		
		//prepare folders
		var folders = ul.find("i.folder, i.template_folder")
		
		if (folders.length > 0) {
			for (var i = folders.length; i >= 0; i--) {
				var elm = $(folders[i]);
				var a = elm.parent();
				var li = a.parent();
				var file_path = a.attr("folder_path");
				var is_template_folder = elm.is("i.template_folder");
				
				var html = '<div class="sub_menu" onClick="openProjectFileSubmenu(this)">'
							+ '<i class="icon sub_menu_vertical"></i>'
							+ '<ul class="jqcontextmenu with_top_right_triangle">'
								+ '<li class="rename"><a onclick="return manageFile(this, \'folder\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename Folder</a></li>'
							+ '<li class="line_break"></li>'
								+ '<li class="remove"><a onclick="return manageFile(this, \'folder\', \'remove\', \'' + file_path + '\', ' + (is_template_folder ? 'onSucccessfullRemoveTemplateFolder' : 'onSucccessfullRemoveFile') + ')">Delete Folder</a></li>'
							+ '</ul>'
						+ '</div>';
				
				a.children("label").after(html);
				
				//ul.prepend(li);
			};
			
			//ul.prepend('<li class="separator">Folders:</li>');
		}
		
		//prepare page files
		var entities = ul.find("i.entity_file");
		//entities.first().parent().closest("li").before('<li class="separator">Pages:</li>');
		entities.each(function(idx, elm) {
			elm = $(elm);
			var a = elm.parent();
			var li = a.parent();
			var file_path = a.attr("file_path");
			var view_url = view_entity_url.replace(/#path#/g, file_path);
			var edit_url = edit_entity_url.replace(/#path#/g, file_path);
			var html = '<div class="sub_menu" onClick="openProjectFileSubmenu(this)">'
						+ '<i class="icon sub_menu_vertical"></i>'
						+ '<ul class="jqcontextmenu with_top_right_triangle">'
							+ '<li class="edit"><a href="' + edit_url + '">Edit Page</a></li>'
							+ '<li class="rename"><a onclick="return manageFile(this, \'file\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename Page</a></li>'
							+ '<li class="line_break"></li>'
							+ '<li class="view_project"><a href="' + view_url + '" target="project">Preview Page</a></li>'
							+ '<li class="line_break"></li>'
							+ '<li class="remove"><a onclick="return manageFile(this, \'file\', \'remove\', \'' + file_path + '\', onSucccessfullRemoveFile)">Delete Page</a></li>'
						+ '</ul>'
					+ '</div>';
			
			a.children("label").after(html);
		});
		
		//prepare template files
		var default_templates_lis = {};
		
		var templates = ul.find("i.template_file");
		var installed_templates_separator = $('<li class="separator">Installed Templates:</li>');
		templates.first().parent().closest("li").before(installed_templates_separator);
		templates.each(function(idx, elm) {
			elm = $(elm);
			var a = elm.parent();
			var li = a.parent();
			var file_path = a.attr("file_path");
			var edit_url = edit_template_url.replace(/#path#/g, file_path);
			var html = '<div class="sub_menu" onClick="openProjectFileSubmenu(this)">'
						+ '<i class="icon sub_menu_vertical"></i>'
						+ '<ul class="jqcontextmenu with_top_right_triangle">'
							+ '<li class="edit"><a href="' + edit_url + '">Edit Template</a></li>'
							+ '<li class="rename"><a onclick="return manageFile(this, \'file\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename Template</a></li>'
							+ '<li class="line_break"></li>'
							+ '<li class="set_default"><a onclick="return setTemplateAsDefault(this, \'' + file_path + '\')">Set Template as Default</a></li>'
							+ '<li class="line_break"></li>'
							+ '<li class="remove"><a onclick="return manageFile(this, \'file\', \'remove\', \'' + file_path + '\', onSucccessfullRemoveFile)">Delete Template</a></li>'
						+ '</ul>'
					+ '</div>';
			
			a.children("label").after(html);
			
			var pos = file_path.indexOf("/src/template/");
			
			if (pos != -1) {
				pos += "/src/template/".length
				var template_id = file_path.substr(pos, file_path.length - pos - 4);
				
				//check if template has a default image in available_templates_props
				if (available_templates_props && !$.isEmptyObject(available_templates_props)) {
					var template_props = available_templates_props[template_id];
					var logo = template_props ? template_props["logo"] : null;
					
					if (logo) {
						var img = $("<img />");
						img.attr("src", logo);
						img.on("error", function() {
							$(this).remove();
						});
						
						a.children("i").append(img);
					}
				}
				
				if ($.inArray(template_id, default_available_templates) != -1) {
					li.addClass("template_" + template_id);
					
					default_templates_lis[template_id] = li[0];
				}
							
				if (template_id == project_default_template) {
					ul.find("li.default_template").removeClass("default_template");
					li.addClass("default_template");
				}
			}
		}).promise().done(function() {
			if (!$.isEmptyObject(default_templates_lis)) {
				for (var i = default_available_templates.length; i >= 0; i--) {
					var template_id = default_available_templates[i];
					
					if (default_templates_lis.hasOwnProperty(template_id))
						ul.prepend( default_templates_lis[template_id] );
				}
				
				ul.children(".separator").first().prev("li").addClass("last_from_group");
				
				ul.prepend('<li class="separator">Default Templates:</li>');
				
				if (installed_templates_separator.next("li").length == 0)
					installed_templates_separator.after('<li class="empty_files">No files availabes...</li>');
			}
		});
		
		//prepare searched files
		searchFiles( ul.parent().closest(".mytree").parent().find(" > .search_file > input")[0] );
	}
}

function openProjectFileSubmenu(elm) {
	window.event.stopPropagation(); //prevent the event to fire in the parent "a" html element.
	
	openSubmenu(elm);
}

function selectMyTreeNode(node) {
	node = $(node);
	var a = node.children("a");
	var i = a.children("i");
	
	if (i.is(".folder, .template_folder")) {
		var path = a.attr("folder_path");
		
		if (path)
			refreshTreeWithNewPath(node, path);
	}
	else if (i.is(".entity_file")) {
		var path = a.attr("file_path");
		document.location = edit_entity_url.replace(/#path#/g, path);
	}
	else if (i.is(".template_file")) {
		var path = a.attr("file_path");
		document.location = edit_template_url.replace(/#path#/g, path);
	}
	
	return false;
}

function refreshTreeWithNewPath(elm, path, mytree_parent_class) {
	var mytree = mytree_parent_class ? $(".admin_panel .project_files > ." + mytree_parent_class + " > .mytree") : $(elm).parent().closest(".mytree");
	var mytree_parent = mytree.parent();
	var mytree_main_li = mytree.children("li");
	var mytree_main_ul = mytree_main_li.children("ul");
	
	path = ("" + path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
	
	updatePathBreadcrumbs(mytree, path);
	
	//replace new path in path url in ul
	var url = mytree_main_ul.attr("url");
	var p = path ? path + "/" : path;
	url = url.replace(/&path=[^&]*/, "&path=" + p);
	mytree_main_ul.attr("url", url);
	mytree_parent.attr("current_path", p);
	
	//reset files searched
	mytree_parent.find(" > .search_file > input").val("");
	
	//refresh ul childs
	refreshAndShowNodeChilds(mytree_main_li);
}

function updatePathBreadcrumbs(mytree, path) {
	var mytree_parent = mytree.parent();
	var is_pages = mytree_parent.is(".pages");
	
	//remove old current_project_folder
	var breadcrumbs = $(".top_bar .breadcrumbs");
	breadcrumbs.find(".breadcrumb-item:not(.fixed)").remove();
	
	var home_label = is_pages ? "pages" : "templates";
	var breadcrumps_html = '<span class="breadcrumb-item"><a href="javascript:void(0)" onClick="refreshTreeWithNewPath(this, \'\', \'' + mytree_parent_class + '\')">' + home_label + '</a></span>';
	
	if (path) { //path could be undefined
		var root_path = mytree_parent.attr("root_path");
		var mytree_parent_class = is_pages ? "pages" : "templates";
		
		path = ("" + path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
		root_path = ("" + root_path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
		
		if (path != root_path) {
			var pos = path.lastIndexOf("/");
			var parent_path = pos != -1 ? path.substr(0, pos) + "/" : "";
			var current_path = path.substr(root_path.length + 1);
			var str = is_pages ? "/src/entity/" : "/src/template/";
			var pos = path.indexOf(str);
			var prefix_path = path.substr(0, pos + str.length);
			
			//add new current_project_folder
			breadcrumps_html = '<span class="breadcrumb-item"><a href="javascript:void(0)" onClick="refreshTreeWithNewPath(this, \'' + prefix_path + '\', \'' + mytree_parent_class + '\')">' + home_label + '</a></span>' + getProjectCurrentFolderHtml(current_path, prefix_path, mytree_parent_class);
		}
	}
	
	breadcrumbs.append(breadcrumps_html);
}

function getProjectCurrentFolderHtml(current_path, prefix_path, mytree_parent_class) {
	current_path = current_path.replace(/^\/+/g, "").replace(/\/+$/g, "");
	var dirs = current_path.split("/");
	var html = '';
	var parent_folder = "";
	
	for (var i = 0; i < dirs.length; i++) {
		var dir = dirs[i];
		
		if (dir) {
			parent_folder += (parent_folder ? "/" : "") + dir;
			
			html += '<span class="breadcrumb-item"><a href="javascript:void(0)" onClick="refreshTreeWithNewPath(this, \'' + prefix_path + parent_folder + '\', \'' + mytree_parent_class + '\');">' + dir + '</a></span>';
		}
	}
	
	return html;
}

function onClickPagesTab() {
	mytree = pagesFromFileManagerTree;
	
	var p = $(".admin_panel .project_files > .pages");
	updatePathBreadcrumbs(p.children(".mytree"), p.attr("current_path"));
}

function onClickTemplatesTab() {
	mytree = templatesFromFileManagerTree;
	
	var p = $(".admin_panel .project_files > .templates");
	updatePathBreadcrumbs(p.children(".mytree"), p.attr("current_path"));
}

function editProject() {
	//get popup
	var popup = $("body > .edit_project_details_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup with_iframe_title edit_project_details_popup"></div>');
		$(document.body).append(popup);
	}
	
	popup.html('<iframe></iframe>'); //cleans the iframe so we don't see the previous html
	
	//prepare popup iframe
	var iframe = popup.children("iframe");
	iframe.attr("src", edit_project_url);
	
	//open popup
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
	});
	
	MyFancyPopup.showPopup();
}

function onSucccessfullEditProject(opts) {
	var url = "" + document.location;
	url = url.indexOf("#") != -1 ? url.substr(0, url.indexOf("#")) : url; //remove # so it can refresh page
	url = url.replace(/[&]+/g, "&");
	
	if (opts && opts["is_rename_project"]) {
		url = url.replace(/(&|\?)filter_by_layout\s*=\s*([^&#]*)/, "");
		url += (url.indexOf("?") != -1 ? "&" : "?") + "filter_by_layout=" + opts["new_filter_by_layout"];
		
		if (window.parent && typeof window.parent.onSucccessfullEditProject == "function") { //if admin_advanced or other admin main page
			window.parent.onSucccessfullEditProject(opts);
			return; //avoids executing the code below.
		}
	}
	
	document.location = url;
}

function toggleProjectsListType(elm, type) {
	elm = $(elm);
	var p = elm.parent();
	
	p.find(".icon").removeClass("active");
	elm.find(".icon").addClass("active");
	
	p.closest(".pages, .templates").children(".mytree").removeClass(type == "block_view" ? "list_view" : "block_view").addClass(type == "block_view" ? "block_view" : "list_view");
}

function searchFiles(elm) {
	elm = $(elm);
	var to_search = elm.val().toLowerCase().replace(/^\s*/, "").replace(/\s*$/, "");
	var mytree = elm.parent().parent().children(".mytree");
	var items = mytree.find("i.folder, i.entity_file, i.template_file, i.template_folder");
	
	items.each(function(idx, i) {
		var a = $(i).parent();
		var li = a.parent();
		var file_name = a.children("label").text();
		var matched = to_search != "" ? file_name.toLowerCase().indexOf(to_search) != -1 : true;
		
		if (matched)
			li.show();
		else
			li.hide();
	});
}

function showCreateFilePopup(elm, path_prefix) {
	//get popup
	var popup = $(".admin_panel > .create_file_popup");
	
	popup.find("button").off().on("click", function() {
		createFile(elm, path_prefix, popup);
	});
	
	//open popup
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
	});
	
	MyFancyPopup.showPopup();
}

function createFile(elm, path_prefix, popup) {
	var type = popup.find(".type > select").val();
	var file_name = popup.find(".name > input").val();
	var action = type == "page" ? "create_file" : "create_folder";
	var handler = function(elm, type, action, path, new_file_name) {
		if (type == "page")
			onSucccessfullCreateFile(elm, type, action, path, new_file_name);
		else
			onSucccessfullCreateFolder(elm, type, action, path, new_file_name);
		
		MyFancyPopup.hidePopup();
	};
	
	if (path_prefix) {
		//get current opened folder and concatenate to path_prefix
		var mytree_parent = $(elm).parent();
		var root_path = mytree_parent.attr("root_path");
		var current_path = mytree_parent.attr("current_path");
		
		root_path = ("" + root_path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
		current_path = current_path ? current_path : "";
		
		var relative_path = current_path.substr(root_path.length + 1);
		path_prefix += relative_path;
	}
	
	manageFile(elm, type, action, path_prefix, handler, file_name);
}

function manageFile(elm, type, action, path, handler, new_file_name) {
	if (path)
		path = path.replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
	
	if (type && action && path) {
		var status = true;
		var action_label = action;
		
		if (type == "project" && action == "remove") 
			status = confirm("Do you wish to delete this project?") && confirm("If you delete this project, you will loose all the created pages and other files inside of this project!\nDo you wish to continue?") && confirm("LAST WARNING:\nIf you proceed, you cannot undo this deletion!\nAre you sure you wish to delete this project?");
		else if (action == "remove") 
			status = confirm("Do you wish to delete this file?")
		else if (action == "rename") {
			var pos = path.lastIndexOf(".");
			var dir_pos = path.lastIndexOf("/");
			
			pos = pos != -1 ? pos : path.length;
			dir_pos = dir_pos != -1 ? dir_pos + 1 : 0;
			
			var dir_name = path.substr(0, dir_pos);
			var base_name = path.substr(dir_pos, pos - dir_pos);
			var extension = pos + 1 < path.length ? path.substr(pos + 1) : "";
			
			if (!new_file_name)
				status = (new_file_name = prompt("Please write the new name:", base_name));
			
			new_file_name = ("" + new_file_name).replace(/^\s+/g, "").replace(/\s+$/g, ""); //trim name
			
			if (status && new_file_name && extension)
				new_file_name += "." + extension;
			else if (!new_file_name)
				status = false;
		}
		else if (action == "create_folder" || action == "create_file") {
			action_label = "create";
			
			if (!new_file_name)
				status = (new_file_name = prompt("Please write the " + type + " name:"));
			
			new_file_name = ("" + new_file_name).replace(/^\s+/g, "").replace(/\s+$/g, ""); //trim name
			
			if (!new_file_name)
				status = false;
		}
		
		if (status) {
			var url = manage_file_url.replace("#action#", action).replace("#path#", path).replace("#extra#", new_file_name);
			
			$.ajax({
				type : "get",
				url : url,
				success : function(data, textStatus, jqXHR) {
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
							StatusMessageHandler.removeLastShownMessage("error");
							removeProject();
						});
					else if (data == "1") {
						StatusMessageHandler.showMessage(type + " " + action_label + "d successfully!");
						
						if (typeof handler == "function")
							handler(elm, type, action, path, new_file_name);
					}
					else
						StatusMessageHandler.showError("There was a problem trying to " + action_label + " " + type + ". Please try again..." + (data ? "\n" + data : ""));
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
					StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to " + action_label + " " + type + ".\nPlease try again..." + msg);
				},
			});
		}
	}
}

function setTemplateAsDefault(elm, path) {
	var node = $(elm).parent().closest(".sub_menu").parent().closest("li");
	var file_path = node.children("a").attr("file_path");
	var pos = file_path.indexOf("/src/template/");
	
	if (pos != -1) {
		pos += "/src/template/".length
		var template_id = file_path.substr(pos, file_path.length - pos - 4);
		
		if (template_id != project_default_template) {
			StatusMessageHandler.showMessage("Setting default template... Loading...");
			
			var obj = {
				project_default_template: template_id,
			};
			var opts = {
				success: function(data, textStatus, jqXHR) {
					StatusMessageHandler.removeLastShownMessage("info");
					
					var mytree = node.parent().closest(".mytree");
					var main_div = mytree.parent();
					
					mytree.find("li.default_template").removeClass("default_template");
					node.addClass("default_template");
					main_div.find(" > .project_default_template > .breadcrumbs").html(template_id);
					
					project_default_template = template_id;
					
					StatusMessageHandler.showMessage("Default template set successfully");
				},
				error: function(jqXHR, textStatus, data) {
					StatusMessageHandler.showError("There was a problem trying to set this template as default. Please try again...") + (data ? "\n" + data : "");
				},
			};
			
			saveObj(save_project_default_template_url, obj, opts);
		}
		else
			StatusMessageHandler.showMessage("This template is already the default template!");
	}
	else
		StatusMessageHandler.showError("This template cannot be set as default!");
}

function refreshNodeParentChildsOnSucccessfullAction(elm) {
	var node_id = $(elm).parent().closest(".sub_menu").parent().closest("li").attr("id");
	refreshNodeParentChildsByChildId(node_id);
}
function onSucccessfullRemoveProject(elm, type, action, path, new_file_name) {
	//show project list page
	document.location = admin_home_page_url;
}
function onSucccessfullRemoveFile(elm, type, action, path, new_file_name) {
	refreshNodeParentChildsOnSucccessfullAction(elm);
}
function onSucccessfullRemoveTemplateFolder(elm, type, action, path, new_file_name) {
	onSucccessfullRemoveFile(elm, type, action, path, new_file_name);
	
	var url = manage_file_url.replace("#action#", action).replace("#path#", path).replace("#extra#", "");
	url = url.replace("/src/template/", "/webroot/template/"); //does not need encodeUrlWeirdChars bc the url is already encoded
	
	$.ajax({
		url : url,
		success : function(data) {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, template_url, function() {
					StatusMessageHandler.removeLastShownMessage("error");
					onSucccessfullRemoveTemplateFolder(elm, type, action, path);
				});
			else if (data == "1") 
				StatusMessageHandler.showMessage("Template webroot deleted successfully");
			else
				StatusMessageHandler.showError("There was a problem trying to delete the correspondent template webroot folder. Please try again...") + (data ? "\n" + data : "");
		},
		error : function(jqXHR, textStatus, errorThrown) {
			var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
			StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to delete template webroot folder.\nPlease try again..." + msg);
		},
	});
}
function onSucccessfullRenameFile(elm, type, action, path, new_file_name) {
	refreshNodeParentChildsOnSucccessfullAction(elm);
}
function onSucccessfullCreateFile(elm, type, action, path, new_file_name) {
	var node = $(elm).parent().find(" > .mytree > li");
	refreshAndShowNodeChilds(node);
	
	//open file to edit
	path += "/" + new_file_name + ".php";
	var edit_url = edit_entity_url.replace(/#path#/g, path);
	document.location = edit_url;
}
function onSucccessfullCreateFolder(elm, type, action, path, new_file_name) {
	var node = $(elm).parent().find(" > .mytree > li");
	refreshAndShowNodeChilds(node);
}

function importTemplates() {
	var url = install_template_url;
	
	//get popup
	var popup = $("body > .import_templates_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup with_iframe_title import_templates_popup"></div>');
		$(document.body).append(popup);
	}
	
	popup.html('<iframe></iframe>'); //cleans the iframe so we don't see the previous html
	
	//prepare popup iframe
	var iframe = popup.children("iframe");
	iframe.attr("src", url);
	
	//open popup
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
	});
	
	MyFancyPopup.showPopup();
}
function onSucccessfullInstallTemplate() {
	StatusMessageHandler.showMessage("Refreshing templates...");
	
	var url = "" + document.location;
	url = url.replace(/&active_tab=[^&]*/g, "");
	url += "&active_tab=1";
	
	document.location = url;
}

