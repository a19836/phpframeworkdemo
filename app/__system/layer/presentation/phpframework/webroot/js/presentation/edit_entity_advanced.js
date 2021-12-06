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
	
	var entity_obj = $(".entity_obj");
	entity_obj.tabs();
	
	initCodeLayoutUIEditor(entity_obj, saveEntity);
	
	onLoadTaskFlowChartAndCodeEditor();
});

function saveEntity() {
	var entity_obj = $(".entity_obj");
	var code = getCodeLayoutUIEditorCode(entity_obj);
	code = getCodeForSaving(entity_obj); //if tasks flow tab is selected ask user to convert workfow into code
	
	var obj = {"code": code};
	
	saveObjCode(save_object_url, obj);
}

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

function toggleEntityHeader(elm) {
	toggleHeader(elm);
	resizeViewTab();
}
