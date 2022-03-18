$(function () {
	$(window).bind('beforeunload', function () {
		if (isTemplateCodeObjChanged()) {
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
	
	choosePresentationFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePresentationFromFileManagerTree.init("choose_presentation_from_file_manager");
	
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
	var template_obj = $(".template_obj");
	
	if (template_obj[0]) {
		template_obj.tabs();
		
		var textarea = $("#code textarea")[0];
		if (textarea) {
			var editor = createCodeEditor(textarea, {save_func: saveTemplate});
			
			if (editor)
				editor.focus();
		}
		
		//init code and task flow editor
		onLoadTaskFlowChartAndCodeEditor();
		
		//set saved_obj_id
		saved_obj_id = getTemplateCodeObjId();
	}
});

function getTemplateCodeObjId() {
	var obj = getTemplateCodeObj();
	
	//remove error messages bc when we call the getCodeForSaving method, it will save try to save the workflow but it will give an error bc we are calling the isTestObjChanged on window before load, which will kill the ongoing ajax requests...
	StatusMessageHandler.removeMessages("error");
	jsPlumbWorkFlow.jsPlumbStatusMessage.removeMessages("error");
	
	$(".workflow_menu").show();
	MyFancyPopup.hidePopup();
	
	return $.md5(save_object_url + JSON.stringify(obj));
}

function isTemplateCodeObjChanged() {
	var template_obj = $(".template_obj");
	
	if (!template_obj[0])
		return false;
	
	return isCodeAndWorkflowObjChanged(template_obj);
}

function getTemplateCodeObj() {
	var template_obj = $(".template_obj");
	
	if (!template_obj[0])
		return null;
	
	code = getCodeForSaving(template_obj); //if tasks flow tab is selected ask user to convert workfow into code
	
	return {"code": code};
}

function saveTemplate() {
	var template_obj = $(".template_obj");
	
	prepareAutoSaveVars();
	
	if (template_obj[0]) {
		if (is_from_auto_save && !isTemplateCodeObjChanged()) {
			resetAutoSave();
			return;
		}
		
		var obj = getTemplateCodeObj();
		
		saveObjCode(save_object_url, obj);
	}
	else if (!is_from_auto_save)
		alert("No template object to save! Please contact the sysadmin...");
}
