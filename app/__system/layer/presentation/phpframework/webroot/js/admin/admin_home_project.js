var pagesFromFileManagerTree = null;
var templatesFromFileManagerTree = null;

$(function() {
	var project_files = $(".admin_panel .project_files");
	project_files.tabs();
	
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
	
	//prepare page files
	ul.find("i.entity_file").each(function(idx, elm) {
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
						+ '<li class="remove"><a onclick="return manageFile(this, \'file\', \'remove\', \'' + file_path + '\', onSucccessfullRemoveFile)">Remove</a></li>'
					+ '</ul>'
				+ '</div>';
		
		a.after(html);
	});
	
	//prepare template files
	ul.find("i.template_file").each(function(idx, elm) {
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("file_path");
		var view_url = edit_template_url.replace(/#path#/g, file_path);
		var html = '<a href="' + view_url + '" class="edit" title="Edit page"><i class="icon edit"></i> Edit</a>'
				+ '<div class="sub_menu">'
					+ '<i class="icon sub_menu"></i>'
					+ '<ul class="jqcontextmenu">'
						+ '<li class="rename"><a onclick="return manageFile(this, \'file\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename</a></li>'
						+ '<li class="remove"><a onclick="return manageFile(this, \'file\', \'remove\', \'' + file_path + '\', onSucccessfullRemoveFile)">Remove</a></li>'
					+ '</ul>'
				+ '</div>';
		
		a.after(html);
	});
	
	//prepare folders
	ul.find("i.folder, i.template_folder").each(function(idx, elm) {
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("folder_path");
		var is_template_folder = elm.is("i.template_folder");
		
		var html = '<div class="sub_menu">'
					+ '<i class="icon sub_menu"></i>'
					+ '<ul class="jqcontextmenu">'
						+ '<li class="rename"><a onclick="return manageFile(this, \'folder\', \'rename\', \'' + file_path + '\', onSucccessfullRenameFile)">Rename</a></li>'
						+ '<li class="remove"><a onclick="return manageFile(this, \'folder\', \'remove\', \'' + file_path + '\', ' + (is_template_folder ? 'onSucccessfullRemoveTemplateFolder' : 'onSucccessfullRemoveFile') + ')">Remove</a></li>'
					+ '</ul>'
				+ '</div>';
		
		a.after(html);
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
	
	return false;
}

function refreshTreeWithNewPath(elm, path) {
	elm = $(elm);
	var mytree = elm.is(".folder_go_up") ? elm.parent().children(".mytree") : elm.parent().closest(".mytree");
	var mytree_parent = mytree.parent();
	var mytree_main_li = mytree.children("li");
	var mytree_main_ul = mytree_main_li.children("ul");
	var root_path = mytree_parent.attr("root_path");
	
	path = ("" + path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
	root_path = ("" + root_path).replace(/\/+/g, "/").replace(/^\/+/g, "").replace(/\/+$/g, ""); //remove duplicates, start and end slash
	
	mytree_parent.children(".folder_go_up").remove(); //remove old folder_go_up btn
	
	if (path != root_path) {
		var pos = path.lastIndexOf("/");
		var parent_path = pos != -1 ? path.substr(0, pos) + "/" : "";
		var folder_go_up = '<a class="folder_go_up" onClick="refreshTreeWithNewPath(this, \'' + parent_path + '\')"><i class="icon go_up"></i> Go to parent folder</a>';
		
		mytree.before(folder_go_up); //add new folder_go_up btn
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
			status = confirm("Do you wish to remove this project?") && confirm("If you delete this project, you will loose all the created pages and other files inside of this project!\nDo you wish to continue?") && confirm("LAST WARNING:\nIf you proceed, you cannot undo this deletion!\nAre you sure you wish to remove this project?");
		else if (action == "remove") 
			status = confirm("Do you wish to remove this file?")
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

function refreshNodeParentChildsOnSucccessfullAction(elm) {
	var node_id = $(elm).parent().closest(".sub_menu").parent().closest("li").attr("id");
	refreshNodeParentChildsByChildId(node_id);
}
function onSucccessfullRemoveProject(elm, type, action, path, new_file_name) {
	//show project list page
	document.location = admin_home_page;
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
	//TODO: Open popup with choose template panel
}
function importTemplates() {
	//TODO: open popup with the install template panel
}
