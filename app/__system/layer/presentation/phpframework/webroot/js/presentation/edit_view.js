var saved_layout_ui_editor_code_id = null;

$(function () {
	$(window).bind('beforeunload', function () {
		if (isViewCodeObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	//prepare top_bar
	$("#ui > .taskflowchart").addClass("with_top_bar_menu fixed_properties").children(".workflow_menu").addClass("top_bar_menu");
	$("#code > .code_menu").addClass("top_bar_menu");
	$("#code > .layout_ui_editor").addClass("with_top_bar_menu");
	
	//init auto save
	enableAutoSave(onTogglePHPCodeAutoSave);
	enableAutoConvert(onTogglePHPCodeAutoConvert);
	initAutoSave("#code > .code_menu li.save a");
	auto_save = false;
	
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
	var view_obj = $(".view_obj");
	
	if (view_obj[0]) {
		view_obj.tabs({active:1});
		
		initCodeLayoutUIEditor(view_obj, saveView);
		
		//add auto_save and auto_convert options to layout ui editor
		var lue_auto_save_icon = $("#code > .code_menu li.auto_save_convert_settings").first().clone().addClass("option");
		$("#code > .layout_ui_editor > .options .full-screen").before(lue_auto_save_icon);
		
		//load workflow
		onLoadTaskFlowChartAndCodeEditor();
		
		//select layout view. Needs to be inside of settimeout otherwise the layout ui editor will not be inited correctly
		setTimeout(function() {
			var luie = view_obj.find("#code > .layout_ui_editor");
			
			//show view layout panel instead of code
			var view_layout = luie.find(" > .tabs > .view-layout");
			view_layout.addClass("do-not-confirm");
			view_obj.find(" > .tabs #visual_editor_tab a").trigger("click");
			view_layout.removeClass("do-not-confirm");
			
			//show php widgets
			luie.find(" > .template-widgets-options .show-php input").attr("checked", "checked").prop("checked", true).trigger("click").attr("checked", "checked").prop("checked", true);
			
			auto_save = true;
		}, 500);
		
		//set saved_layout_ui_editor_code_id
		saved_layout_ui_editor_code_id = getViewLayoutUIEditorCodeObjId();
	}
});

function getViewLayoutUIEditorCodeObjId() {
	var view_obj = $(".view_obj");
	var layout_ui_editor_code_id = getCodeLayoutUIEditorCode(view_obj);
	
	return $.md5(layout_ui_editor_code_id);
}

function isViewCodeObjChanged() {
	var view_obj = $(".view_obj");
	
	if (!view_obj[0])
		return false;
	
	if(isCodeAndWorkflowObjChanged(view_obj))
		return true;
	
	var new_layout_ui_editor_code_id = getViewLayoutUIEditorCodeObjId();
	
	return saved_layout_ui_editor_code_id != new_layout_ui_editor_code_id;
}

function getViewCodeObj() {
	var view_obj = $(".view_obj");
	
	if (!view_obj[0])
		return null;
	
	//simply call this function so it can generate the code if on the visual tab in the layout ui editor.
	getCodeLayoutUIEditorCode(view_obj); 
	
	//gets code
	var code = getCodeForSaving(view_obj); //if tasks flow tab is selected ask user to convert workfow into code
	
	return {"code": code};
}

function saveView() {
	var view_obj = $(".view_obj");
	
	prepareAutoSaveVars();
	
	if (view_obj[0]) {
		if (is_from_auto_save && !isViewCodeObjChanged()) {
			resetAutoSave();
			return;
		}
		
		var obj = getViewCodeObj();
		
		saveObjCode(save_object_url, obj, {
			success: function(data, textStatus, jqXHR) {
				//update saved_layout_ui_editor_code_id
				saved_layout_ui_editor_code_id = getViewLayoutUIEditorCodeObjId();
				
				return true;
			},
		});
	}
	else if (!is_from_auto_save)
		alert("No view object to save! Please contact the sysadmin...");
}
