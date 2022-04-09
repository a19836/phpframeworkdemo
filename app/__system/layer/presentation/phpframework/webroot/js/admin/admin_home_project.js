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
			
			var html = '<div class="sub_menu">'
						+ '<i class="icon sub_menu"></i>'
						+ '<ul class="jqcontextmenu">'
							+ '<li class="rename"><a onclick="return manageFile(this, \'folder\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename</a></li>'
							+ '<li class="remove"><a onclick="return manageFile(this, \'folder\', \'remove\', \'' + file_path + '\', ' + (is_template_folder ? 'onSucccessfullRemoveTemplateFolder' : 'onSucccessfullRemoveFile') + ')">Delete</a></li>'
						+ '</ul>'
					+ '</div>';
			
			a.after(html);
			
			ul.prepend(li);
		};
		
		ul.prepend('<li class="separator">Folders:</li>');
	}
	
	//prepare page files
	var entities = ul.find("i.entity_file");
	entities.first().parent().closest("li").before('<li class="separator">Pages:</li>');
	entities.each(function(idx, elm) {
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("file_path");
		var view_url = view_entity_url.replace(/#path#/g, file_path);
		var edit_url = edit_entity_url.replace(/#path#/g, file_path);
		var html = '<a href="' + view_url + '" class="preview" title="Preview page" target="project"><i class="icon view"></i> Preview</a>'
				+ '<a href="' + edit_url + '" class="edit" title="Edit page"><i class="icon edit"></i> Edit</a>'
				+ '<div class="sub_menu">'
					+ '<i class="icon sub_menu"></i>'
					+ '<ul class="jqcontextmenu">'
						+ '<li class="rename"><a onclick="return manageFile(this, \'file\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename</a></li>'
						+ '<li class="remove"><a onclick="return manageFile(this, \'file\', \'remove\', \'' + file_path + '\', onSucccessfullRemoveFile)">Delete</a></li>'
					+ '</ul>'
				+ '</div>';
		
		a.after(html);
	});
	
	//prepare template files
	var default_templates_lis = {};
	
	var templates = ul.find("i.template_file");
	templates.first().parent().closest("li").before('<li class="separator">Installed Templates:</li>');
	templates.each(function(idx, elm) {
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("file_path");
		var edit_url = edit_template_url.replace(/#path#/g, file_path);
		var html = '<a href="' + edit_url + '" class="edit" title="Edit page"><i class="icon edit"></i> Edit</a>'
				+ '<div class="sub_menu">'
					+ '<i class="icon sub_menu"></i>'
					+ '<ul class="jqcontextmenu">'
						+ '<li class="set_default"><a onclick="return setTemplateAsDefault(this, \'' + file_path + '\')">Set as Default</a></li>'
						+ '<li class="rename"><a onclick="return manageFile(this, \'file\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename</a></li>'
						+ '<li class="remove"><a onclick="return manageFile(this, \'file\', \'remove\', \'' + file_path + '\', onSucccessfullRemoveFile)">Delete</a></li>'
					+ '</ul>'
				+ '</div>';
		
		a.after(html);
		
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
			
			ul.prepend('<li class="separator">Default Templates:</li>');
		}
	});
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

function refreshTreeWithNewPath(elm, path) {
	elm = $(elm);
	var mytree = elm.is(".folder_go_up") ? elm.parent().children(".mytree") : (
		elm.parent().is(".current_project_folder") ? elm.parent().parent().children(".mytree") : elm.parent().closest(".mytree")
	);
	var mytree_parent = mytree.parent();
	var mytree_main_li = mytree.children("li");
	var mytree_main_ul = mytree_main_li.children("ul");
	var root_path = mytree_parent.attr("root_path");
	
	path = ("" + path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
	root_path = ("" + root_path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
	
	mytree_parent.children(".current_project_folder, .folder_go_up").remove(); //remove old folder_go_up btn
	
	if (path != root_path) {
		var pos = path.lastIndexOf("/");
		var parent_path = pos != -1 ? path.substr(0, pos) + "/" : "";
		var current_path = path.substr(root_path.length + 1);
		var str = mytree_parent.is(".pages") ? "/src/entity/" : "/src/template/";
		var pos = path.indexOf(str);
		var prefix_path = path.substr(0, pos + str.length);
		
		var current_project_folder = '<div class="current_project_folder" title="Current folder"><span onClick="refreshTreeWithNewPath(this, \'' + prefix_path + '\')" class="icon folder"></span> ' + getProjectCurrentFolderHtml(current_path, prefix_path) + '</div>';
		var folder_go_up = '<div class="folder_go_up" onClick="refreshTreeWithNewPath(this, \'' + parent_path + '\')"><i class="icon go_up"></i> Go to parent folder</div>';
		
		mytree.before(folder_go_up).before(current_project_folder); //add new current_project_folder and folder_go_up btn
	}
	
	//replace new path in path url in ul
	var url = mytree_main_ul.attr("url");
	var p = path ? path + "/" : path;
	url = url.replace(/&path=[^&]*/, "&path=" + p);
	mytree_main_ul.attr("url", url);
	mytree_parent.attr("current_path", p);
	
	//refresh ul childs
	refreshAndShowNodeChilds(mytree_main_li);
}

function getProjectCurrentFolderHtml(current_path, prefix_path) {
	current_path = current_path.replace(/^\/+/g, "").replace(/\/+$/g, "");
	var dirs = current_path.split("/");
	var html = '';
	var parent_folder = "";
	
	for (var i = 0; i < dirs.length; i++) {
		var dir = dirs[i];
		
		if (dir) {
			parent_folder += (parent_folder ? "/" : "") + dir;
			
			html += '<span class="path_parts" onClick="refreshTreeWithNewPath(this, \'' + prefix_path + parent_folder + '\');">' + dir + '</span>';
		}
	}
	
	return html;
}

function onClickPagesTab() {
	mytree = pagesFromFileManagerTree;
}

function onClickTemplatesTab() {
	mytree = templatesFromFileManagerTree;
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

function onSucccessfullEditProject() {
	var url = "" + document.location;
	url = url.indexOf("#") != -1 ? url.substr(0, url.indexOf("#")) : url; //remove # so it can refresh page
	
	document.location = url;
}

function createFile(elm, type, action, path, handler) {
	if (path) {
		//get current opened folder and concatenate to path
		var mytree_parent = $(elm).parent();
		var root_path = mytree_parent.attr("root_path");
		var current_path = mytree_parent.attr("current_path");
		
		root_path = ("" + root_path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
		current_path = current_path ? current_path : "";
		
		var relative_path = current_path.substr(root_path.length + 1);
		path += relative_path;
	}
	
	manageFile(elm, type, action, path, handler);
}

function manageFile(elm, type, action, path, handler) {
	if (path)
		path = path.replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
	
	if (type && action && path) {
		var status = true;
		var new_file_name = "";
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
			
			status = (new_file_name = prompt("Please write the new name:", base_name));
			new_file_name = ("" + new_file_name).replace(/^\s+/g, "").replace(/\s+$/g, ""); //trim name
			
			if (status && new_file_name && extension)
				new_file_name += "." + extension;
			else if (!new_file_name)
				status = false;
		}
		else if (action == "create_folder" || action == "create_file") {
			action_label = "create";
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
					main_div.find(" > .project_default_template > span").html(template_id);
					
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

function browseTemplates() {
	importTemplates(true);
}

function importTemplates(open_store) {
	var url = install_template_url;
	
	if (open_store)
		url += "&open_store=1";
	
	//get popup
	var popup = $("body > .import_templates_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup with_iframe_title import_templates_popup' + (open_store ? " open_store" : "") + '"></div>');
		$(document.body).append(popup);
	}
	
	popup.html('<iframe scrolling="no"></iframe>'); //cleans the iframe so we don't see the previous html
	
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

