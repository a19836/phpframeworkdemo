var chooseTemplateTaskLayerFileFromFileManagerTree = null;
var chooseTestUnitsFromFileManagerTree = null;
var MyDeploymentUIFancyPopup = new MyFancyPopupClass();

$(function() {
	/*$(window).bind('beforeunload', function () {
		if (window.parent && window.parent.iframe_overlay)
			window.parent.iframe_overlay.hide();
		
		return "Changes you made may not be saved. Click cancel to save them first, otherwise to continue...";
	});*/
	
	jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_connector = "Flowchart";
	jsPlumbWorkFlow.jsPlumbTaskFlow.default_connection_overlay = "One To One";
	//jsPlumbWorkFlow.jsPlumbTaskFlow.available_connection_connectors_type = ["Flowchart"];
	jsPlumbWorkFlow.jsPlumbTaskFlow.available_connection_overlays_type = ["One To One"];
	
	jsPlumbWorkFlow.jsPlumbTaskFile.on_success_read = updateTasksAfterFileRead;
	jsPlumbWorkFlow.jsPlumbTaskFile.on_success_update = updateTasksAfterFileRead;
	
	chooseTemplateTaskLayerFileFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllInvalidTemplateTaskLayerFilesFromTree,
	});
	chooseTemplateTaskLayerFileFromFileManagerTree.init("choose_template_task_layer_file_from_file_manager");
	
	chooseTestUnitsFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllInvalidTestUnitsFromTree,
	});
	chooseTestUnitsFromFileManagerTree.init("choose_test_units_from_file_manager");
	
	$("#choose_template_task_layer_file_from_file_manager > .mytree > li:first-child > a").attr("file_path", "");
	$("#choose_test_units_from_file_manager > .mytree > li:first-child > a").attr("file_path", "");
});

function removeAllInvalidTemplateTaskLayerFilesFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.function, i.reserved_file").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	ul.find("i.file, i.service, i.project, i.project_common").each(function(idx, elm){
		$(elm).parent().parent().children("ul").remove();
	});
}

function removeAllInvalidTestUnitsFromTree(ul, data) {
	ul = $(ul);
	
	ul.find("i.function").each(function(idx, elm){
		$(elm).parent().parent().remove();
	});
	
	ul.find("i.file").each(function(idx, elm){
		elm = $(elm);
		var a = elm.parent();
		var li = a.parent();
		var file_path = a.attr("file_path");
		
		if (!file_path || !("" + file_path).match(/\.php([0-9]*)$/i)) //is not a php file
			li.remove();
		else if (li.find(" > ul > li > a").children("i.test_unit_obj").length == 0)
			li.remove();
	});
}

function onChooseTemplateTaskLayerFile(elm, layer_name) {
	if (layer_name) {
		layer_name = ("" + layer_name).toLowerCase();
		
		var popup = $("#choose_template_task_layer_file_from_file_manager");
		var broker = popup.children(".broker");
		var select = broker.children("select");
		
		broker.hide();
		select.val(layer_name);
		
		if (popup.attr("layer_name") != layer_name) {
			popup.attr("layer_name", layer_name);
			updateTemplateTaskLayerUrlFileManager(select[0]);
		}
		
		MyDeploymentUIFancyPopup.init({
			elementToShow: popup,
			parentElement: document,
			
			targetField: $(elm).parent().children("input")[0],
			updateFunction: function(elm) {
				chooseFile(elm, chooseTemplateTaskLayerFileFromFileManagerTree);
			},
		});
		
		MyDeploymentUIFancyPopup.showPopup();
	}
}

function onGetLayerWordPressInstallationsUrl(layer_name) {
	var url = null;
	
	if (layer_name) {
		layer_name = ("" + layer_name).toLowerCase();
		
		var popup = $("#choose_template_task_layer_file_from_file_manager");
		var options = popup.find(".broker select option");
		
		$.each(options, function (idx, option) {
			option = $(option);
			
			if (option.val() == layer_name) {
				url = option.attr("url");
				
				if (url) 
					url = url.replace("#path#", wordpress_installations_relative_path);
				
				return false;
			}
		});
	}
	
	return url;
}

function onChooseTemplateActionTestUnit(elm) {
	var popup = $("#choose_test_units_from_file_manager");
	
	MyDeploymentUIFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: $(elm).parent().children("input")[0],
		updateFunction: function(elm) {
			chooseFile(elm, chooseTestUnitsFromFileManagerTree);
		},
	});
	
	MyDeploymentUIFancyPopup.showPopup();
}

function chooseFile(elm, treeObj) {
	var node = treeObj.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		
		if (a[0].hasAttribute("file_path")) {
			$(MyDeploymentUIFancyPopup.settings.targetField).val( a.attr("file_path") ); //if file_path is empty it means it is the root of the layer
	
			MyDeploymentUIFancyPopup.hidePopup();
		}
		else 
			alert("Selected item must be a valid file or folder!\nPlease try again...");
	}
}

function updateTemplateTaskLayerUrlFileManager(elm) {
	var option = elm.options[ elm.selectedIndex ];
	var url = option.getAttribute("url");
	
	var mytree = $(elm).parent().parent().find(".mytree");
	var root_elm = mytree.children("li").first();
	var ul = root_elm.children("ul").first();
	
	root_elm.removeClass("jstree-open").addClass("jstree-closed");
	ul.html("");
	ul.attr("url", url);
}

function prepareTaskContextMenu() {
	/*;(function() {
		jsPlumbWorkFlow.onReady(function() {
			$("#" + jsPlumbWorkFlow.jsPlumbContextMenu.task_context_menu_id + " .set_label a").html("Edit Server Name");
		});
	})();*/
}

function updateTasksAfterFileRead() {
	$(".loading_panel").hide();
}

function saveDBDiagram() {
	jsPlumbWorkFlow.jsPlumbTaskFile.save(null, {
		success: function(data, textStatus, jqXHR) {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url, function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					saveDBDiagram();
				});
		},
	});

	return false;
}

function addNewServer() {
	var server_task_type_id = ServerTaskPropertyObj.template_tasks_types_by_tag["server"];
	var task_id = jsPlumbWorkFlow.jsPlumbContextMenu.addTaskByType(server_task_type_id);
	
	jsPlumbWorkFlow.jsPlumbTaskFlow.setTaskLabelByTaskId(task_id, {label: null}); //set {label: null}, so the jsPlumbTaskFlow.setTaskLabel method ignores the prompt and adds the default label or an auto generated label.
	
	//open properties
	jsPlumbWorkFlow.jsPlumbProperty.showTaskProperties(task_id);
}
