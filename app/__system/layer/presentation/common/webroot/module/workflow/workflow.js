$(function () {
	var module_workflow_settings = $(".module_workflow_settings");
	module_workflow_settings.parent().closest(".block_obj").children(".buttons").children("input").attr("onclick", "saveModuleWorkflowSettings(this);");
	
	//remove duplicated choose_from_file_manager bc of them were already created in the edit_block_simple.php
	var cfm = $(".choose_from_file_manager");
	for (var i = 0, l = cfm.length; i < l; i++) {
		var id = cfm[i].getAttribute("id");
		
		for (var j = i + 1; j < l; j++)
			if (cfm[j].getAttribute("id") == id)
				$(cfm[j]).remove();
	}
	
	//prepare trees file managers
	
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	//chooseMethodFromFileManagerTree was already inited in the module_join_points.js
	if (!chooseMethodFromFileManagerTree) {
		chooseMethodFromFileManagerTree = new MyTree({
			multiple_selection : false,
			ajax_callback_before : prepareLayerNodes1,
			ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForMethods,
		});
		chooseMethodFromFileManagerTree.init("choose_method_from_file_manager");
	}
	
	//chooseFunctionFromFileManagerTree was already inited in the module_join_points.js
	if (!chooseFunctionFromFileManagerTree) {
		chooseFunctionFromFileManagerTree = new MyTree({
			multiple_selection : false,
			ajax_callback_before : prepareLayerNodes1,
			ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForFunctions,
		});
		chooseFunctionFromFileManagerTree.init("choose_function_from_file_manager");
	}
	
	//chooseFileFromFileManagerTree was already inited in the module_join_points.js
	if (!chooseFileFromFileManagerTree) {
		chooseFileFromFileManagerTree = new MyTree({
			multiple_selection : false,
			ajax_callback_before : prepareLayerNodes1,
			ajax_callback_after : removeObjectPropertiesAndFunctionsFromTree,
		});
		chooseFileFromFileManagerTree.init("choose_file_from_file_manager");
	}
	
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
	
	module_workflow_settings.children(".module_workflow_content").tabs();
	
	var textarea = module_workflow_settings.find("> .module_workflow_content > #code textarea")[0];
	if (textarea) {
		var editor = createCodeEditor(textarea, {save_func: saveModuleWorkflowSettings});
		if (editor) {
			editor.focus();
		}
	}
	
	onLoadTaskFlowChartAndCodeEditor();
});

function loadModuleWorkflowSettingsBlockSettings(settings_elm, settings_values) {
	if (settings_values) {
		if (settings_values["code"]) {
			var code = settings_values["code"]["value"];
			
			if (code) {
				if (settings_values["code"]["value_type"] != "string") {
					alert("Error: Code is not a string. You will be now redirected to the advanced interface...");
					document.location = show_edit_block_advanced_url;
				}
				else {
					//must remove \\$ and \\ bc the code will be always between '' and back-slashes and dollar symbols will always be escaped. So we need to unescape them.
					code = code.replace(/\\\$/g, '$');
					code = code.replace(/\\\\/g, '\\');
					
					var editor = $("#code").data("editor");

					if (editor)
						editor.setValue(code, 1);
					else
						settings_elm.find("#code textarea").val(code);
				}
			}
		}
		
		if (settings_values["external_vars"]) {
			var block_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
			external_vars = block_values["external_vars"];
			
			if ($.isArray(external_vars) || $.isPlainObject(external_vars)) {
				var add_elm = settings_elm.find("#external_vars table thead .add");
				
				$.each(external_vars, function(external_var_name, external_var_value) {
					var row = addExternalVar(add_elm[0]);
					
					row.find(".variable_name input").val(external_var_name);
					row.find(".variable_value input").val(external_var_value);
				});
			}
		}
	}
}

function addExternalVar(elm) {
	elm = $(elm);
	var tbody = elm.parent().closest("table").children("tbody");
	tbody.children(".no_external_variables").hide();
	
	var index = getListNewIndex(tbody);
	
	var row = '<tr>'
			+ '<td class="variable_name">'
				+ '<input class="module_settings_property" type="text" name="external_vars[' + index + '][name]" />'
				+ '<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
			+ '</td>'
			+ '<td class="variable_value">'
				+ '<input class="module_settings_property" type="text" name="external_vars[' + index + '][value]" />'
				+ '<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>'
			+ '</td>'
			+ '<td class="action">'
				+ '<i class="icon delete" onclick="removeExternalVar(this)"></i>'
			+ '</td>'
		+ '</tr>';
	
	row = $(row);
	tbody.append(row);
	
	return row;
}

function removeExternalVar(elm) {
	if (confirm("Do you wish to remove this external var?")) {
		elm = $(elm);
		var tr = elm.parent().closest("tr");
		var tbody = tr.parent();
		
		tr.remove();
		
		if (tbody.children().length == 1)
			tbody.children(".no_external_variables").show();
	}
}

function saveModuleWorkflowSettings(button) {
	var module_workflow_content = $(".module_workflow_content");
	var code = getCodeForSaving(module_workflow_content);
	$(".workflow_menu").show();
	
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var settings = getBlockSettingsObjForSaving( module_workflow_content.find("#external_vars") );
	settings["code"] = code;
	
	if (settings["external_vars"]) {
		var external_vars = {};
		$.each(settings["external_vars"], function(idx, external_var) {
			if (external_var["name"])
				external_vars[ external_var["name"] ] = external_var["value"];
		});
		settings["external_vars"] = external_vars;
	}
	
	$.ajax({
		type : "post",
		url : create_workflow_settings_code_url,
		data : settings,
		dataType : "json",
		success : function(data, textStatus, jqXHR) {
			if (data && data["code"])
				saveBlockRawCode(data["code"]);
			else
				StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
			
			MyFancyPopup.hidePopup();
		},
		error : function(jqXHR, textStatus, errorThrown) { 
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_workflow_settings_code_url, function() {
					jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
					StatusMessageHandler.removeLastShownMessage("error");
					saveModuleWorkflowSettings(button);
				});
			else
				StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
			
			MyFancyPopup.hidePopup();
		},
	});
}
