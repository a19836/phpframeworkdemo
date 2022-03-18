$(function () {
	$(window).bind('beforeunload', function () {
		if (isConfigCodeObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare top_bar
	$("#ui > .taskflowchart").addClass("with_top_bar_menu fixed_properties").children(".workflow_menu").addClass("top_bar_menu");
	
	//init auto save
	enableAutoSave(onTogglePHPCodeAutoSave);
	enableAutoConvert(onTogglePHPCodeAutoConvert);
	
	initAutoSave("#code > .code_menu li.save a");
	
	//init trees
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
	
	choosePageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePageUrlFromFileManagerTree.init("choose_page_url_from_file_manager");
	
	chooseImageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotAPossibleImageFromTree,
	});
	chooseImageUrlFromFileManagerTree.init("choose_image_url_from_file_manager");
	
	//init ui
	var config_obj = $(".config_obj");
	
	if (config_obj[0]) {
		config_obj.tabs({active: show_low_code_first ? 1 : 0});
		
		var textarea = $("#code textarea")[0];
		if (textarea) {
			var editor = createCodeEditor(textarea, {save_func: saveConfig});
			
			if (editor)
				editor.focus();
		}
		
		//load workflow
		onLoadTaskFlowChartAndCodeEditor();
		
		//init tasks flow tab
		onClickTaskWorkflowTab( config_obj.find(" > .tabs > #tasks_flow_tab > a")[0], {
			on_success: function() {
				//set saved_obj_id
				saved_obj_id = getConfigCodeObjId();
			},
			on_error: function() {
				config_obj.tabs("option", "active", 0); //show code tab
				
				//set saved_obj_id
				saved_obj_id = getConfigCodeObjId();
			}
		});
	}
});

function getConfigCodeObjId() {
	var obj = getConfigCodeObj();
	
	//remove error messages bc when we call the getCodeForSaving method, it will save try to save the workflow but it will give an error bc we are calling the isTestObjChanged on window before load, which will kill the ongoing ajax requests...
	StatusMessageHandler.removeMessages("error");
	jsPlumbWorkFlow.jsPlumbStatusMessage.removeMessages("error");
	
	$(".workflow_menu").show();
	MyFancyPopup.hidePopup();
	
	return $.md5(save_object_url + JSON.stringify(obj));
}

function isConfigCodeObjChanged() {
	var config_obj = $(".config_obj");
	
	if (!config_obj[0])
		return false;
	
	return isCodeAndWorkflowObjChanged(config_obj);
}

function getConfigCodeObj() {
	var config_obj = $(".config_obj");
	
	if (!config_obj[0])
		return null;
	
	var code = getCodeForSaving(config_obj); //if tasks flow tab is selected ask user to convert workfow into code
	
	return {"code": code};
}

function saveConfig() {
	var config_obj = $(".config_obj");
	
	prepareAutoSaveVars();
		
	if (config_obj[0]) {
		if (is_from_auto_save && !isConfigCodeObjChanged()) {
			resetAutoSave();
			return;
		}
		
		var obj = getConfigCodeObj();
		
		saveObjCode(save_object_url, obj);
	}
	else if (!is_from_auto_save)
		alert("No config object to save! Please contact the sysadmin...");
}
