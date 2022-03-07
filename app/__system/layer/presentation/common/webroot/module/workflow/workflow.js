var saved_workflow_obj_id = null;
var saved_workflow_settings_id = null;

$(function () {
	//unbind beforeunload that was inited by the edit_simple_block.js
	$(window).unbind('beforeunload').bind('beforeunload', function () {
		if (isModuleWorkflowObjChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	$(".top_bar .save a").attr("onClick", "saveModuleWorkflowSettings(this);");
	$("#ui > .taskflowchart").addClass("with_top_bar_menu fixed_properties").children(".workflow_menu").addClass("top_bar_menu");
	
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
	
	//init module_workflow_settings
	var module_workflow_settings = $(".module_workflow_settings");
	var module_workflow_content = module_workflow_settings.children(".module_workflow_content");
	module_workflow_content.tabs({active: show_low_code_first ? 1 : 0});
	
	var textarea = module_workflow_settings.find("> .module_workflow_content > #code textarea")[0];
	if (textarea) {
		var editor = createCodeEditor(textarea, {save_func: saveModuleWorkflowSettings});
		if (editor) {
			editor.focus();
		}
	}
	
	//add auto_save and auto_convert options
	$("#code .code_menu ul li.auto_save_convert_settings ul .auto_save_activation input, #ui .taskflowchart .workflow_menu ul.dropdown li.auto_save_convert_settings ul .auto_save_activation input").attr("onChange", "toggleAutoSaveCheckbox(this, onToggleModuleWorkflowAutoSave)");
	$("#code .code_menu ul li.auto_save_convert_settings ul .auto_convert_activation input, #ui .taskflowchart .workflow_menu ul.dropdown li.auto_save_convert_settings ul .auto_convert_activation input").attr("onChange", "toggleAutoConvertCheckbox(this, onToggleModuleWorkflowAutoConvert)");
	
	if (auto_save)
		enableAutoSave(onToggleModuleWorkflowAutoSave);
	else
		disableAutoSave(onToggleModuleWorkflowAutoSave);
	
	enableAutoConvert(onToggleModuleWorkflowAutoConvert);
	
	$(".top_bar li.auto_save_convert_settings").remove(); //remove auto_save_menu bc we will add another one below...
	var auto_save_icon = $("#code > .code_menu .auto_save_convert_settings").clone();
	$(".top_bar li.save").before(auto_save_icon);
	
	//load workflow
	onLoadTaskFlowChartAndCodeEditor();
	
	//init tasks flow tab
	onClickTaskWorkflowTab( module_workflow_content.find(" > .tabs > #tasks_flow_tab > a")[0], {
		on_success: function() {
			//set saved settings id
			saved_workflow_obj_id = getModuleWorkflowObjId();
			saved_workflow_settings_id = getModuleWorkflowSettingsId();
		},
		on_error: function() {
			module_workflow_content.tabs("option", "active", 0); //show code tab
			
			//set saved settings id
			saved_workflow_obj_id = getModuleWorkflowObjId();
			saved_workflow_settings_id = getModuleWorkflowSettingsId();
		}
	});
});

function onToggleModuleWorkflowAutoSave() {
	onTogglePHPCodeAutoSave();
	
	var inputs = $(".top_bar li.auto_save_convert_settings ul li.auto_save_activation input");
	
	if (auto_save)
		inputs.attr("checked", "checked").prop("checked", true);
	else
		inputs.removeAttr("checked").prop("checked", false);
}

function onToggleModuleWorkflowAutoConvert() {
	onTogglePHPCodeAutoConvert();
	
	var inputs = $(".top_bar li.auto_save_convert_settings ul li.auto_convert_activation input");
	
	if (auto_convert)
		inputs.attr("checked", "checked").prop("checked", true);
	else
		inputs.removeAttr("checked").prop("checked", false);
}

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

function isModuleWorkflowObjChanged() {
	var new_workflow_settings_id = getModuleWorkflowSettingsId();
	
	//remove error messages bc when we call the getCodeForSaving method, it will save try to save the workflow but it will give an error bc we are calling the isTestObjChanged on window before load, which will kill the ongoing ajax requests...
	//StatusMessageHandler.removeMessages("error");
	//jsPlumbWorkFlow.jsPlumbStatusMessage.removeMessages("error");
	
	var code = getEditorCodeRawValue();
	var new_code_id = $.md5(code);
	var old_code_id = $(".module_workflow_content > #ui").attr("code_id");
	
	var old_workflow_id = $(".module_workflow_content > #ui").attr("workflow_id");
	var new_workflow_id = getCurrentWorkFlowId();
	
	var selected_tab = $(".module_workflow_content > ul").find("li.ui-tabs-selected, li.ui-tabs-active").first();
	
	return saved_workflow_settings_id != new_workflow_settings_id
		|| old_workflow_id != new_workflow_id
		|| old_code_id != new_code_id;
		(selected_tab.attr("id") == "tasks_flow_tab" && jsPlumbWorkFlow.jsPlumbTaskFile.isWorkFlowChangedFromLastSaving()); //compares if tasks' sizes and offsets are different, but only if workflow tab is selected.
}

function getModuleWorkflowSettingsId() {
	var settings = getModuleWorkflowSettings();
	
	return $.md5(JSON.stringify(settings));
}

function getModuleWorkflowObjId() {
	var obj = getModuleWorkflowObj();
	
	$(".workflow_menu").show();
	MyFancyPopup.hidePopup();
	
	return $.md5(JSON.stringify(obj));
}

function getModuleWorkflowObj() {
	var obj = getModuleWorkflowSettings();
	
	var module_workflow_content = $(".module_workflow_content");
	var code = getCodeForSaving(module_workflow_content);
	obj["code"] = code;
	
	return obj;
}

function getModuleWorkflowSettings() {
	var module_workflow_content = $(".module_workflow_content");
	var settings = getBlockSettingsObjForSaving( module_workflow_content.find("#external_vars") );
	
	if (settings["external_vars"]) {
		var external_vars = {};
		$.each(settings["external_vars"], function(idx, external_var) {
			if (external_var["name"])
				external_vars[ external_var["name"] ] = external_var["value"];
		});
		settings["external_vars"] = external_vars;
	}
	
	return settings;
}

function saveModuleWorkflowSettings(button) {
	prepareAutoSaveVars();
	
	var obj = getModuleWorkflowObj();
	var new_workflow_obj_id = $.md5(JSON.stringify(obj)); //Do not call getModuleWorkflowObjId here otherwise it will execute twice the getCodeForSaving method which may generate twice the workflow based on code
	
	if (!saved_workflow_obj_id || saved_workflow_obj_id != new_workflow_obj_id) {
		if (!is_from_auto_save) {
			MyFancyPopup.showOverlay();
			MyFancyPopup.showLoading();
		}
		
		$.ajax({
			type : "post",
			url : create_workflow_settings_code_url,
			data : obj,
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				if (data && data["code"]) {
					if (saveBlockRawCode(data["code"])) {
						saved_workflow_obj_id = new_workflow_obj_id; //set new saved_str_id
						saved_workflow_settings_id = getModuleWorkflowSettingsId(); //update saved_workflow_settings_id
					}
				}
				else if (!is_from_auto_save)
					StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
				
				if (!is_from_auto_save) {
					MyFancyPopup.hidePopup();
					$(".workflow_menu").show(); //show workflow_menu hidden by the getCodeForSaving method, if a manual save action
				}
				else
					resetAutoSave();
			},
			error : function(jqXHR, textStatus, errorThrown) { 
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_workflow_settings_code_url, function() {
						jsPlumbWorkFlow.jsPlumbStatusMessage.removeLastShownMessage("error");
						StatusMessageHandler.removeLastShownMessage("error");
						saveModuleWorkflowSettings(button);
					});
				else if (!is_from_auto_save)
					StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
				
				if (!is_from_auto_save) {
					MyFancyPopup.hidePopup();
					$(".workflow_menu").show(); //show workflow_menu hidden by the getCodeForSaving method, if a manual save action
				}
				else
					resetAutoSave();
			},
		});
	}
	else if (!is_from_auto_save) {
		StatusMessageHandler.showMessage("Nothing to save.");
		
		MyFancyPopup.hidePopup(); //the getModuleWorkflowObjId executed the showPopup, so we must hide it
		$(".workflow_menu").show(); //show workflow_menu hidden by the getCodeForSaving method, if a manual save action
	}
	else
		resetAutoSave();
}
