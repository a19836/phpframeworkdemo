$(function () {
	/*$(window).bind('beforeunload', function () {
		if (window.parent && window.parent.iframe_overlay)
			window.parent.iframe_overlay.hide();
		
		return "Changes you made may not be saved. Click cancel to save them first, otherwise to continue...";
	});*/
	
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	chooseMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForMethods,
	});
	chooseMethodFromFileManagerTree.init("choose_method_from_file_manager");
	
	chooseFunctionFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForFunctions,
	});
	chooseFunctionFromFileManagerTree.init("choose_function_from_file_manager");
	
	chooseFileFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
	});
	chooseFileFromFileManagerTree.init("choose_file_from_file_manager");
	
	$(".global_vars_obj").tabs({
		active: is_code_valid ? 2 : 0,
	});
	
	var textarea = $("#code textarea")[0];
	if (textarea) {
		var editor = createCodeEditor(textarea, {save_func: saveGlobalVariablesAdvanced});
		if (editor)
			editor.focus();
	}
	
	onLoadTaskFlowChartAndCodeEditor();
});

function addNewVariable(elm) {
	var table = $(elm).parent().parent().parent();
	
	table.append(global_var_html);
}

function onIncludeFileTaskChooseFileForProjectGlobalVars(elm) {
	var popup = $("#choose_file_from_file_manager");
	
	MyFancyPopup.init({
		elementToShow: popup,
		parentElement: document,
		
		targetField: $(elm).parent(),
		updateFunction: chooseIncludeFileForProjectGlobalVars
	});
	
	MyFancyPopup.showPopup();
}

function chooseIncludeFileForProjectGlobalVars(elm) {
	var node = chooseFileFromFileManagerTree.getSelectedNodes();
	node = node[0];
	
	if (node) {
		var a = $(node).children("a");
		var file_path = a.attr("file_path");
		var bean_name = a.attr("bean_name");
		
		if (file_path) {
			var parts = current_relative_file_path.replace(/\/\//g, "").split("/");
			var inc_1 = "";
			var inc_2 = "";
			for (var i = 0; i < parts.length; i++) {
				inc_1 += "dirname(";
				inc_2 += ")";
			}
			
			var include_path = inc_1 + "__FILE__" + inc_2;
			
			if (bean_name == "dao" || bean_name == "lib" || bean_name == "vendor" || bean_name == "test_unit")
				include_path = 'dirname(dirname(' + include_path + ')) . "' + bean_name.toLowerCase() + '/' + file_path + '"';
			else if (layer_type == "pres")
				include_path += ' . "' + file_path + '"';
			
			MyFancyPopup.settings.targetField.children("input").val(include_path);
			MyFancyPopup.settings.targetField.parent().find(".type select").val("");
			
			//This is for the presentation task: includes and includes_once items.
			MyFancyPopup.settings.targetField.children(".value_type").val("");
			MyFancyPopup.settings.targetField.children(".includes_type").val("");
			MyFancyPopup.settings.targetField.children(".includes_once_type").val("");
		
			MyFancyPopup.hidePopup();
		}
		else {
			alert("invalid selected file.\nPlease choose a valid file.");
		}
	}
}

function onChooseAvailableTemplate(elm) {
	chooseAvailableTemplate( $(elm).parent().parent().find(" > .var_value select")[0], {
		show_templates_only: true
	} );
}

function saveGlobalVariablesSimple(elm) {
	var table = $(elm).parent().children(".vars");
	
	var vars_name = table.find("td .var_name");
	var vars_value = table.find("td .var_value");
	
	var vars_name_data = [];
	var vars_value_data = [];
	
	for (var i = 0; i < vars_name.length; i++) {
		vars_name_data.push( $(vars_name[i]).val() );
		vars_value_data.push( $(vars_value[i]).val() );
	}
	
	var obj = {
		"code": "",
		"vars_name": vars_name_data, 
		"vars_value": vars_value_data,
	};
	
	saveObjCode(save_object_simple_url, obj);
}

function saveGlobalVariablesAdvanced() {
	var global_vars_obj = $(".global_vars_obj");
	
	var code = getCodeForSaving(global_vars_obj);
	var obj = {"code": code};
	
	saveObjCode(save_object_advanced_url, obj, {
		error: function() {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, [ save_object_advanced_url, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url], function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					StatusMessageHandler.removeLastShownMessage("error");
					saveGlobalVariablesAdvanced();
				});
		},
	});
}
