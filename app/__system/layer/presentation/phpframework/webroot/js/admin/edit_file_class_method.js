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
	
	if (layer_type == "pres" || layer_type == "bl") {
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
		
		if (layer_type == "pres") {
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
		}
	}
		
	$(".file_class_method_obj").tabs();
	
	var textarea = $("#code textarea")[0];
	if (textarea) {
		eval("var save_func = " + js_save_func_name + ";");
		
		createCodeEditor(textarea, {save_func: save_func});
	}
	
	onLoadTaskFlowChartAndCodeEditor();
});

function addNewArgument(elm) {
	var html_obj = $(new_argument_html);
	$(elm).parent().parent().parent().parent().find(".fields").append(html_obj);
	
	return html_obj;
}

function addNewAnnotation(elm) {
	var html_obj = $(new_annotation_html);
	$(elm).parent().parent().parent().parent().find(".fields").append(html_obj);
	
	return html_obj;
}

function swapTypeTextField(elm) {
	var p = $(elm).parent();
	var select = p.children("select");
	var input = p.children("input");
	
	if (select.css("display") == "none") {
		input.css("display", "none");
		select.css("display", "inline");
		select.val( input.val() );
	}
	else {
		select.css("display", "none");
		input.css("display", "inline");
		input.val( select.val() );
	}
}

function getFileClassMethodObj() {
	var file_class_method_obj = $(".file_class_method_obj");
	var code = getCodeForSaving(file_class_method_obj, {strip_php_tags: true}); //if tasks flow tab is selected ask user to convert workfow into code
	var settings_elm = file_class_method_obj.children("#settings");

	var arguments = [];
	var items = settings_elm.children(".arguments").find(".fields .argument");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
	
		var name = item.find(".name input").val();
		var value = item.find(".value input").val();
		var var_type = item.find(".var_type select").val();
	
		arguments.push({
			"name": name,
			"value": value,
			"var_type": var_type,
		});
	}
	
	var annotations = {};
	var items = settings_elm.children(".annotations").find(".fields .annotation");
	for (var i = 0; i < items.length; i++) {
		var item = $(items[i]);
		
		var annotation_type = item.find(".annotation_type select").val();
		
		if (!annotations.hasOwnProperty(annotation_type)) {
			annotations[annotation_type] = [];
		}
		
		annotations[annotation_type].push({
			"name": item.find(".name input").val(), 
			"type": item.find(".type select").css("display") == "none" ? item.find(".type input").val() : item.find(".type select").val(), 
			"not_null": item.find(".not_null input").is(":checked") ? 1 : 0, 
			"default": item.find(".default input").val(), 
			"others": item.find(".others input").val(), 
			"desc": item.find(".description input").val(),
		});
	}

	var obj = {
		"name": settings_elm.children(".name").children("input").val(),
		"type": settings_elm.children(".type").children("select").val(),
		"abstract": settings_elm.children(".abstract").children("input").prop("checked") ? 1 : 0,
		"static": settings_elm.children(".static").children("input").prop("checked") ? 1 : 0,
		"arguments": arguments,
		"annotations": annotations,
		"comments": settings_elm.children(".comments").children("textarea").val(),
		"code": code,
	};
	
	return obj;
}

function saveFileClassMethod() {
	var obj = getFileClassMethodObj();
	var save_url = save_object_url.replace("#method_id#", original_method_id);
	
	saveObjCode(save_url, obj, {
		success: function(data, textStatus, jqXHR) {
			if (original_method_id != obj["name"]) {
				original_method_id = obj["name"];
				
				if (window.parent && typeof window.parent.refreshLastNodeParentChilds == "function")
					window.parent.refreshLastNodeParentChilds();
			}
			
			return true;
		}
	});
}
