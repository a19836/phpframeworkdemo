var saved_layout_ui_editor_code_id = null;

$(function () {
	$(window).bind('beforeunload', function () {
		if (isEntityCodeObjChanged()) {
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
	
	//init ui
	var entity_obj = $(".entity_obj");
	
	if (entity_obj[0]) {
		entity_obj.tabs({active:1});
		
		//load workflow
		onLoadTaskFlowChartAndCodeEditor();
		
		//set saved_layout_ui_editor_code_id
		saved_layout_ui_editor_code_id = getEntityLayoutUIEditorCodeObjId();
		
		//init ui layout editor
		initCodeLayoutUIEditor(entity_obj, saveEntity, function() {
			var luie = entity_obj.find("#code > .layout_ui_editor");
			
			//show view layout panel instead of code
			/*var view_layout = luie.find(" > .tabs > .view-layout");
			view_layout.addClass("do-not-confirm");
			entity_obj.find(" > .tabs #visual_editor_tab a").trigger("click");
			view_layout.removeClass("do-not-confirm");*/
			
			//show php widgets
			luie.find(" > .template-widgets-options .show-php input").attr("checked", "checked").prop("checked", true).trigger("click").attr("checked", "checked").prop("checked", true);
			
			//init auto save
			enableAutoSave(onTogglePHPCodeAutoSave);
			enableAutoConvert(onTogglePHPCodeAutoConvert);
			initAutoSave("#code > .code_menu li.save a");
			
			//add auto_save and auto_convert options to layout ui editor
			var lue_auto_save_icon = $("#code > .code_menu li.auto_save_convert_settings").first().clone().addClass("option");
			$("#code > .layout_ui_editor > .options .full-screen").before(lue_auto_save_icon);
			
			if (!luie.find(" > .tabs > .tab.tab-active").is(".view-layout"))
				lue_auto_save_icon.addClass("hidden"); //bc the LayoutUIEditor is not inited at start, we need to hide this new icon. The others are already hidden by default.
		});
	}
});

//To be used in the toggleFullScreen function
function onToggleFullScreen(in_full_screen) {
	var entity_obj = $(".entity_obj");
	onToggleCodeEditorFullScreen(in_full_screen, entity_obj);
}

function getEntityLayoutUIEditorCodeObjId() {
	var entity_obj = $(".entity_obj");
	var layout_ui_editor_code_id = getCodeLayoutUIEditorCode(entity_obj);
	
	return $.md5(layout_ui_editor_code_id);
}

function isEntityCodeObjChanged() {
	var entity_obj = $(".entity_obj");
	
	if (!entity_obj[0])
		return false;
	
	if(isCodeAndWorkflowObjChanged(entity_obj))
		return true;
	
	var new_layout_ui_editor_code_id = getEntityLayoutUIEditorCodeObjId();
	
	return saved_layout_ui_editor_code_id != new_layout_ui_editor_code_id;
}

function getEntityCodeObj() {
	var entity_obj = $(".entity_obj");
	
	if (!entity_obj[0])
		return null;
	
	//simply call this function so it can generate the code if on the visual tab in the layout ui editor.
	getCodeLayoutUIEditorCode(entity_obj); 
	
	//gets code
	var code = getCodeForSaving(entity_obj); //if tasks flow tab is selected ask user to convert workfow into code
	
	return {"code": code};
}

function saveEntity() {
	var entity_obj = $(".entity_obj");
	
	prepareAutoSaveVars();
	
	if (entity_obj[0]) {
		if (is_from_auto_save && !isEntityCodeObjChanged()) {
			resetAutoSave();
			return;
		}
		
		var obj = getEntityCodeObj();
		
		saveObjCode(save_object_url, obj, {
			success: function(data, textStatus, jqXHR) {
				//update saved_layout_ui_editor_code_id
				saved_layout_ui_editor_code_id = getEntityLayoutUIEditorCodeObjId();
				
				return true;
			},
		});
	}
	else if (!is_from_auto_save)
		alert("No entity object to save! Please contact the sysadmin...");
}

/* VIEW TAB FUNCTIONS */
function resizeViewTab() {
	var view = $(".entity_obj #view");
	var offset = view.offset();
	var top = parseInt(offset.top + 1) + 10;

	var h = parseInt( $(window).height() ) - top;
	view.css("height", h + "px");
	view.children("iframe").css("height", h + "px");
}

function onClickViewTab(elm, view_file_path) {
	if (!elm.hasAttribute("is_init")) {
		MyFancyPopup.showOverlay();
		MyFancyPopup.showLoading();
		
		elm.setAttribute("is_init", 1);
		
		var selector = $( elm.getAttribute("href") );
		
		var iframe = $('<iframe src="' + view_file_path + '"></iframe>');
		selector.append(iframe);
		
		iframe.load(function(){
			MyFancyPopup.hidePopup();
			
			setTimeout(function() {
				resizeViewTab();
			}, 300);
		});
		
		$(window).resize(function() {
			resizeViewTab();
		});
	}
	
	setTimeout(function() {
		resizeViewTab();
	}, 1000);
}

function onClickNewViewTab(elm, add_view_file_url) {
	var obj = {"code": ""};
	
	saveObjCode(add_view_file_url, obj, {
		success: function(data, textStatus, jqXHR) {
			elm = $(elm);
			var li = elm.parent();
			var ul = li.parent();
			var view_tab = ul.children("#view_tab");
			
			li.remove();
			view_tab.removeClass("hidden");
			view_tab.children("a").trigger("click");
		}
	});
}
