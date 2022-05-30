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
	$("#ui > .taskflowchart").addClass("with_top_bar_menu fixed_side_properties").children(".workflow_menu").addClass("top_bar_menu");
	$("#code > .code_menu").addClass("top_bar_menu");
	$("#code > .layout-ui-editor").addClass("with_top_bar_menu");
	
	//init trees
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	chooseMethodFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForMethods,
	});
	chooseMethodFromFileManagerTree.init("choose_method_from_file_manager");
	
	chooseFunctionFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForFunctions,
	});
	chooseFunctionFromFileManagerTree.init("choose_function_from_file_manager");
	
	chooseFileFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
	});
	chooseFileFromFileManagerTree.init("choose_file_from_file_manager");
	
	choosePresentationFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePresentationFromFileManagerTree.init("choose_presentation_from_file_manager");
	
	choosePageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotPagesFromTree,
	});
	choosePageUrlFromFileManagerTree.init("choose_page_url_from_file_manager");
	
	chooseImageUrlFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_children_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeAllThatIsNotAPossibleImageFromTree,
	});
	chooseImageUrlFromFileManagerTree.init("choose_image_url_from_file_manager");
	
	//init ui
	var view_obj = $(".view_obj");
	
	if (view_obj[0]) {
		view_obj.tabs({active:1});
		
		//load workflow
		onLoadTaskFlowChartAndCodeEditor();
		
		//set saved_layout_ui_editor_code_id
		saved_layout_ui_editor_code_id = getViewLayoutUIEditorCodeObjId();
		
		//init ui layout editor
		initCodeLayoutUIEditor(view_obj, {
			save_func: saveView, 
			ready_func: function() {
				var luie = view_obj.find("#code > .layout-ui-editor");
				var PtlLayoutUIEditor = luie.data("LayoutUIEditor");
				
				if (PtlLayoutUIEditor)
					PtlLayoutUIEditor.options.on_panels_resize_func = onResizeCodeLayoutUIEditorWithRightContainer;
				
				//show view layout panel instead of code
				var view_layout = luie.find(" > .tabs > .view-layout");
				view_layout.addClass("do-not-confirm");
				view_obj.find(" > .tabs #visual_editor_tab a").trigger("click");
				view_layout.removeClass("do-not-confirm");
				
				//show php widgets, borders and background
				PtlLayoutUIEditor.showTemplateWidgetsDroppableBackground();
				PtlLayoutUIEditor.showTemplateWidgetsBorders();
				PtlLayoutUIEditor.showTemplatePHPWidgets();
				
				//init auto save
				enableAutoSave(onTogglePHPCodeAutoSave);
				enableAutoConvert(onTogglePHPCodeAutoConvert);
				initAutoSave("#code > .code_menu li.save a");
				
				//add auto_save and auto_convert options to layout ui editor
				var sub_menu = $('<i class="icon sub_menu option"><ul></ul></i>');
				$("#code > .layout-ui-editor > .options .full-screen").before(sub_menu);
				var lue_full_screen_icon = $("#code > .code_menu li.editor_full_screen").first().clone().removeClass("hidden").addClass("without_padding");
				var flip_layout_ui_panels_icon = $('<li class="flip_layout_ui_panels without_padding" title="Flip Layout UI Panels"><a onClick="flipCodeLayoutUIEditorPanelsSide(this)"><i class="icon flip_layout_ui_panels"></i> Flip Layout UI Panels</a></li>');
				var lue_save_icon = $("#code > .code_menu li.save").first().clone().removeClass("hidden").addClass("without_padding");
				var lue_auto_save_icon = $("#code > .code_menu li.auto_save_activation").first().clone().removeClass("hidden");
				var lue_auto_convert_icon = $("#code > .code_menu li.auto_convert_activation").first().clone().removeClass("hidden");
				sub_menu.children("ul").append(flip_layout_ui_panels_icon).append(lue_full_screen_icon).append('<li class="separator"></li>').append(lue_auto_save_icon).append(lue_auto_convert_icon).append(lue_save_icon);
				
				if (!luie.find(" > .tabs > .tab.tab-active").is(".view-layout"))
					sub_menu.addClass("hidden"); //bc the LayoutUIEditor is not inited at start, we need to hide this new icon. The others are already hidden by default.
			}, 
		});
	}
});

//To be used in the toggleFullScreen function
function onToggleFullScreen(in_full_screen) {
	var view_obj = $(".view_obj");
	onToggleCodeEditorFullScreen(in_full_screen, view_obj);
}

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
