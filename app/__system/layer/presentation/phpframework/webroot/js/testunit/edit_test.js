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
	
	chooseBusinessLogicFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
	});
	chooseBusinessLogicFromFileManagerTree.init("choose_business_logic_from_file_manager");
	
	chooseQueryFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeParametersAndResultMapsFromTree,
	});
	chooseQueryFromFileManagerTree.init("choose_query_from_file_manager");
	
	chooseHibernateObjectFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeQueriesAndMapsAndOtherHbnNodesFromTree,
	});
	chooseHibernateObjectFromFileManagerTree.init("choose_hibernate_object_from_file_manager");
	
	chooseHibernateObjectMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeParametersAndResultMapsFromTree,
	});
	chooseHibernateObjectMethodFromFileManagerTree.init("choose_hibernate_object_method_from_file_manager");
	
	choosePresentationFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePresentationFromFileManagerTree.init("choose_presentation_from_file_manager");
	
	chooseBlockFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotBlocksFromTree,
	});
	chooseBlockFromFileManagerTree.init("choose_block_from_file_manager");
	
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
	
	$(".edit_test").tabs();
	
	var textarea = $("#code textarea")[0];
	if (textarea) {
		createCodeEditor(textarea, {save_func: saveTest});
		
		onLoadTaskFlowChartAndCodeEditor();
	}
	else
		MyFancyPopup.hidePopup();
});

function addNewGlobalVariableFilePath(elm) {
	var html_obj = $(new_global_variables_file_path_html);
	$(elm).parent().parent().parent().parent().find(".fields").append(html_obj);
	
	return html_obj;
}

function addNewAnnotation(elm) {
	var html_obj = $(new_annotation_html);
	$(elm).parent().parent().parent().parent().find(".fields").append(html_obj);
	
	return html_obj;
}

function saveTest() {
	var edit_test = $(".edit_test");
	
	var code = getCodeForSaving(edit_test, {strip_php_tags: true});
	
	var settings_elm = edit_test.children("#settings");
	
	var global_variables_files_path = [];
	var items = settings_elm.children(".global_variables_files_path").find(".fields .global_variables_file_path");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
	
		var path = item.find(".path input").val();
	
		global_variables_files_path.push({
			"path": path,
		});
	}
	
	var annotations = {};
	var items = settings_elm.children(".annotations").find(".fields .annotation");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
		
		var annotation_type = item.find(".annotation_type select").val();
		
		if (!annotations.hasOwnProperty(annotation_type)) 
			annotations[annotation_type] = [];
		
		annotations[annotation_type].push({
			"path": item.find(".path input").val(), 
			"others": item.find(".others input").val(), 
			"desc": item.find(".description input").val(),
		});
	}

	var obj = {
		"enabled": settings_elm.children(".enabled").children("input").prop("checked") ? 1 : 0,
		"global_variables_files_path": global_variables_files_path,
		"annotations": annotations,
		"comments": settings_elm.children(".comments").children("textarea").val(),
		"code": code,
	};
	
	saveObjCode(save_object_url, obj, {
		error: function() {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, [ save_object_url, jsPlumbWorkFlow.jsPlumbTaskFile.set_tasks_file_url ], function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					StatusMessageHandler.removeLastShownMessage("error");
					saveTest();
				});
		},
	});
}
