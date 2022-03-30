var saved_settings_id = null;

$(function () {
	//unbind beforeunload that was inited by the edit_simple_block.js
	$(window).unbind('beforeunload').bind('beforeunload', function () {
		if (isModuleShowObjectsGroupSettingsChanged()) {
			if (window.parent && window.parent.iframe_overlay)
				window.parent.iframe_overlay.hide();
			
			return "If you proceed your changes won't be saved. Do you wish to continue?";
		}
		
		return null;
	});
	
	$(".top_bar .save a").attr("onclick", "saveModuleShowObjectsGroupSettings(this);");
	
	$(".module_show_objects_group_settings").children(".create_form_task_html").children(".form_input, .form_input_data").remove();
	
	if (choose_from_file_manager_popup_html) {
		$(document.body).append( $(choose_from_file_manager_popup_html).children("#choose_property_variable_from_file_manager") );
	}
	
	choosePropertyVariableFromFileManagerTree = new MyTree({
		multiple_selection : false,
		toggle_chils_on_click : true,
		ajax_callback_before : prepareLayerNodes1,
		ajax_callback_after : removeObjectPropertiesAndMethodsFromTreeForVariables,
	});
	choosePropertyVariableFromFileManagerTree.init("choose_property_variable_from_file_manager .class_prop_var");
	
	//set saved settings id
	saved_settings_id = getModuleShowObjectsGroupSettingsId();
	
	MyFancyPopup.hidePopup();
});

function loadShowObjectsGroupBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var block_settings = settings_elm.children(".module_show_objects_group_settings");
	
	//Preparing some html items first
	var objects_groups_by_id = {};
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_data"),
		success: function(data) {
			if (data) {
				var objects_groups = data["objects_groups"];
				var object_types = data["object_types"];
				
				var options = '';
				$.each(object_types, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.find(".list_by_parent .list_parent_object_type_id select").html(options);
				
				options = '';
				$.each(objects_groups, function(index, objects_group) {
					objects_groups_by_id[ objects_group["objects_group_id"] ] = objects_group;
					
					options += '<option value="' + objects_group["objects_group_id"] + '">' + objects_group["object"] + '</option>';
				});
				block_settings.find(".list_by_selected_objects_groups .available_objects_groups select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load all objects_groups and object_types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	//Preparing Form Settings
	if (js_load_function) {
		var create_form_task_html = block_settings.children(".create_form_task_html");
		var tasks_values = convertSettingsToTasksValues(settings_values);
		
		jsPlumbWorkFlow.jsPlumbProperty.setPropertiesFromHtmlElm(create_form_task_html, "task_property_field", tasks_values);
		js_load_function(block_settings, null, tasks_values);
		
		create_form_task_html.children(".inline_settings").children(".with_form, .form_method").hide();
	}
	
	//Preparing Action Settings
	var action_values = settings_values && settings_values.hasOwnProperty("action_settings") && !jQuery.isEmptyObject(settings_values["action_settings"]) && settings_values["action_settings"].hasOwnProperty("items") ? settings_values["action_settings"]["items"] : null;
	
	if (action_values) {
		//Preparing generic fields
		loadBlockSettings(block_settings, action_values);
		
		//Preparing Object to Objects Group
		loadObjectToObjectsBlockSettings(block_settings.children(".action_settings"), action_values, "object_to_objects");
		
		//Preparing Others
		updateObjectsGroupsSelectionType( block_settings.find(".objects_groups_type select")[0] );
		
		var objects_group_ids = prepareBlockSettingsItemValue(action_values["objects_group_ids"]);
		
		if (!jQuery.isEmptyObject(objects_groups_by_id) && $.isArray(objects_group_ids)) {
			var table = block_settings.find(".list_by_selected_objects_groups .selected_objects_groups table").first();
			table.find(".no_objects_groups").hide();
		
			var html = '';
		
			for (var i = 0; i < objects_group_ids.length; i++) {
				var objects_group_id = objects_group_ids[i];
			
				if (!jQuery.isEmptyObject(objects_groups_by_id[objects_group_id])) {
					html += getObjectsGroupHtml(objects_group_id, objects_groups_by_id[objects_group_id]["object"]);
				}
			}
			
			table.append(html);
		}
	
		//Preparing Action Buttons
		var action_buttons = action_values.hasOwnProperty("action_buttons") && !jQuery.isEmptyObject(action_values["action_buttons"]) && action_values["action_buttons"].hasOwnProperty("items") ? action_values["action_buttons"]["items"] : null;
		action_buttons = convertBlockSettingsValuesIntoBasicArray(action_buttons);
	
		loadActionButtonsSettings(block_settings, action_buttons);
	}
	
	MyFancyPopup.hidePopup();
}

function convertSettingsToTasksValues(settings_values) {
	var tasks_values = {};
	
	if (!$.isEmptyObject(settings_values)) {
		if (settings_values["form_settings"]) {
			if (settings_values["form_settings"]["value"]) {
				tasks_values["form_settings_data_type"] = settings_values["form_settings"]["value_type"];
				tasks_values["form_settings_data"] = settings_values["form_settings"]["value"];
			}
			else if (settings_values["form_settings"]["items"]) {
				tasks_values["form_settings_data_type"] = "array";
				tasks_values["form_settings_data"] = convertObjectIntoArray(settings_values["form_settings"]["items"]);
			}
		}
		
		tasks_values = convertBlockSettingsValuesKeysToLowerCase(tasks_values);
	}
	
	return tasks_values;
}

function getObjectsGroupHtml(objects_group_id, object) {
	return '<tr class="objects_group">'
	+ '	<td class="objects_group_id">' + objects_group_id + '</td>'
	+ '	<td class="objects_group_object">' + object + '</td>'
	+ '	<td class="buttons">'
	+ '		<input class="module_settings_property" type="hidden" name="objects_group_ids[]" value="' + objects_group_id + '" />'
	+ '		<span class="icon up" onClick="moveSelectedObjectsGroupUp(this)">Move Up</span>'
	+ '		<span class="icon down" onClick="moveSelectedObjectsGroupDown(this)">Move Down</span>'
	+ '		<span class="icon delete" onClick="removeSelectedObjectsGroup(this)">Remove</span>'
	+ '	</td>'
	+ '</tr>';
}

function addSelectedObjectsGroup(elm) {
	var p = $(elm).parent();
	var select = p.children("select");
	var objects_group_id = select.val();
	var objects_group_object = select.find(":selected").text();
	
	var table = p.parent().find(".selected_objects_groups table");
	
	var exists = table.find("tr.objects_group .buttons input[value='" + objects_group_id + "']");
	if (exists[0]) {
		StatusMessageHandler.showError("Objects Group already exists!");
	}
	else {
		var html = getObjectsGroupHtml(objects_group_id, objects_group_object);
		table.append(html);
		table.find(".no_objects_groups").hide();
	}
}

function moveSelectedObjectsGroupUp(elm) {
	moveRegionBlock(elm, "up");
}

function moveSelectedObjectsGroupDown(elm) {
	moveRegionBlock(elm, "down");
}

function moveRegionBlock(elm, direction) {
	var item = $(elm).parent().parent();
	
	if (direction == "up") {
		var prev = item.prev();
		if (prev.hasClass("objects_group"))
			item.insertBefore(prev);
	}
	else {
		var next = item.next();
		if (next.hasClass("objects_group"))
			item.insertAfter(next);
	}
}

function removeSelectedObjectsGroup(elm) {
	var tr = $(elm).parent().parent();
	var table = tr.parent();
	tr.remove();
	
	if (table.find("tr.objects_group").length == 0) {
		table.find(".no_objects_groups").show();
	}
}

function updateObjectsGroupsSelectionType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var block_settings = elm.parent().parent();
	
	block_settings.children(".list_by_parent, .list_by_tags, .list_by_selected_objects_groups, .specific_by_id").hide();
	block_settings.find(".list_by_parent > .list_parent_group").hide();
	
	if (value == "specific") {
		block_settings.children(".specific_by_id").show();
	}
	else if (value == "tags_and" || value == "tags_or" || value == "parent_tags_and" || value == "parent_tags_or" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		block_settings.children(".list_by_tags").show();
	}
	else if (value == "selected") {
		block_settings.children(".list_by_selected_objects_groups").show();
	}
	
	if (value == "parent" || value == "parent_tags_and" || value == "parent_tags_or") {
		block_settings.children(".list_by_parent").show();
	}
	else if (value == "parent_group" || value == "parent_group_tags_and" || value == "parent_group_tags_or") {
		var cp = block_settings.children(".list_by_parent");
		cp.show();
		cp.children(".list_parent_group").show();
	}
}

function loadActionButtonsSettings(block_settings, action_buttons) {
	if (!jQuery.isEmptyObject(action_buttons) && action_buttons.hasOwnProperty("button_label")) {
		var buttons_labels = action_buttons["button_label"];
		var action_types = action_buttons["action_type"];
		var action_variables = action_buttons["action_variable"];
		
		if ($.isArray(buttons_labels)) {
			var add_icon = block_settings.children(".action_settings").children(".action_buttons_settings").children(".action_buttons").find("th.buttons .add")[0];
			
			for (var i = 0; i < buttons_labels.length; i++) {
				var item = addActionButton(add_icon);
				
				item.find(".button_label input").val( buttons_labels[i] );
				item.find(".action_type select").val( action_types[i] );
				item.find(".action_variable input").val( action_variables[i] );
			}
		}
	}
}

function getActionButtonHtml() {
	var actions_types = {
		"insert_objects_group": "Insert 1 Objects Group",
		"insert_objects_groups": "Insert Multiple Objects Groups",
		"update_objects_group": "Update 1 Objects Group",
		"update_objects_groups": "Update Multiple Objects Groups",
		"save_objects_group": "Save (insert or update if exists) 1 Objects Group",
		"save_objects_groups": "Save (insert or update if exists) Multiple Objects Groups",
		"delete_objects_group": "Delete 1 Objects Group",
		"delete_objects_groups": "Delete Multiple Objects Groups",
	};
	
	var html = ''
	+ '<tr class="action_button">'
	+ '	<td class="button_label"><input type="text" class="module_settings_property" name="action_buttons[button_label][]" value="" /></td>'
	+ '	<td class="action_type"><select class="module_settings_property" name="action_buttons[action_type][]">'
	+ '		<option value="">-- None --</option>';
	
	for (var key in actions_types) {
		html += '<option value="' + key + '">' + actions_types[key] + '</option>';
	}
	
	html += ''
	+ '	</select></td>'
	+ '	<td class="action_variable"><input type="text" class="module_settings_property" name="action_buttons[action_variable][]" value="" /></td>'
	+ '	<td class="icons">'
	+ '		<span class="icon up" onClick="moveActionButtonUp(this)">Move Up</span>'
	+ '		<span class="icon down" onClick="moveActionButtonDown(this)">Move Down</span>'
	+ '		<span class="icon delete" onClick="removeActionButton(this)">Remove</span>'
	+ '	</td>'
	+ '</tr>';
	
	return html;
}

function addActionButton(elm) {
	var html = $(getActionButtonHtml());
	var table = $(elm).parent().parent().parent();
	table.append(html);
	
	table.children(".no_action_buttons").hide();
	
	return html;
}

function moveActionButtonUp(elm) {
	var field = $(elm).parent().parent();
	var prev = field.prev();
	
	if (prev.children("th").length == 0)
		prev.before(field);
}

function moveActionButtonDown(elm) {
	var field = $(elm).parent().parent();
	field.next().after(field);
}

function removeActionButton(elm) {
	var tr = $(elm).parent().parent();
	var table = tr.parent();
	
	tr.remove();
	
	if (table.children("tr").length == 2)
		table.children(".no_action_buttons").show();
}

function isModuleShowObjectsGroupSettingsChanged() {
	var new_settings_id = getModuleShowObjectsGroupSettingsId();
	
	return saved_settings_id != new_settings_id;
}

function getModuleShowObjectsGroupSettingsId() {
	var settings = getModuleShowObjectsGroupSettings();
	
	return $.md5(JSON.stringify(settings));
}

function getModuleShowObjectsGroupSettings() {
	var settings = {};
	
	var block_settings = $(".block_obj > .module_settings > .settings > .module_show_objects_group_settings");
	var create_form_task_html = block_settings.children(".create_form_task_html");
	
	/*if (create_form_task_html.children(".inline_settings").children(".with_form").children("select").val() != "1" && block_settings.children(".action_settings").children(".action_buttons_settings").children(".action_buttons").find("td.button_label").length > 0) {
		StatusMessageHandler.showError("If you have action buttons, you must have the \"With Form\" setting active.\nIn order to proceed, please change the \"Without Form\" option to \"With Form\" option.");
		return false;
	}*/
	
	settings["form_settings_data_type"] = create_form_task_html.find(".form_settings select").val();
	
	if (settings["form_settings_data_type"] == "array") {
		var form_settings_data = parseArray( create_form_task_html.children(".form_settings_data") );
		settings["form_settings_data"] = form_settings_data["form_settings_data"];
	}
	else if (settings["form_settings_data_type"] == "settings") {
		CreateFormTaskPropertyObj.prepareCssAndJsFieldsToSave(create_form_task_html);
		
		var form_settings_data = FormFieldsUtilObj.convertFormSettingsDataSettingsToArray( create_form_task_html.children(".inline_settings") );
		ArrayTaskUtilObj.onLoadArrayItems( create_form_task_html.children(".form_settings_data"), form_settings_data, "");
		
		var form_settings_data = parseArray( create_form_task_html.children(".form_settings_data") );
		settings["form_settings_data"] = form_settings_data["form_settings_data"];
		settings["form_settings_data_type"] = "array";
	}
	else if (settings["form_settings_data_type"] == "ptl") {
		var ptl_settings = create_form_task_html.find(".ptl_settings");
		var code =   getPtlElementTemplateSourceEditorValue(ptl_settings, true);
		var external_vars = {};
		
		$.each( ptl_settings.find(" > .ptl_external_vars .item"), function (idx, item) {
			item = $(item);
			var k = item.children(".key").val();
			var v = item.children(".value").val();
			
			if (k && v)
				external_vars[ k.charAt(0) == "$" ? k.substr(1) : k ] = v.charAt(0) == "$" ? v : "$" + v;
		});
		
		settings["form_settings_data"] = {"ptl" : {
			"code" : code,
			"input_data_var_name" : ptl_settings.find(" > .input_data_var_name > input").val(),
			"idx_var_name" : ptl_settings.find(" > .idx_var_name > input").val(),
			"external_vars" : external_vars,
		}};
		settings["form_settings_data_type"] = "ptl";
	}
	else
		settings["form_settings_data"] = create_form_task_html.find(".form_settings input").val();
	
	settings["action_settings"] = getBlockSettingsObjForSaving( block_settings.children(".action_settings") );
	
	//console.log(settings);
	
	return settings;
}

function saveModuleShowObjectsGroupSettings(button) {
	prepareAutoSaveVars();
	
	var new_settings_id = getModuleShowObjectsGroupSettingsId();
	
	if (!saved_settings_id || saved_settings_id != new_settings_id) {
		if (!is_from_auto_save) {
			MyFancyPopup.showOverlay();
			MyFancyPopup.showLoading();
		}
		
		var settings = getModuleShowObjectsGroupSettings();
		
		$.ajax({
			type : "post",
			url : create_form_settings_code_url,
			data : {"settings" : settings},
			dataType : "json",
			success : function(data, textStatus, jqXHR) {
				if (data && data["code"]) {
					if (saveBlockRawCode(data["code"]))
						saved_settings_id = new_settings_id; //set new saved_str_id
				}
				else if (!is_from_auto_save)
					StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
				
				if (!is_from_auto_save)
					MyFancyPopup.hidePopup();
				else
					resetAutoSave();
			},
			error : function() { 
				if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
					showAjaxLoginPopup(jquery_native_xhr_object.responseURL, create_form_settings_code_url, function() {
						StatusMessageHandler.removeLastShownMessage("error");
						
						saveModuleShowObjectsGroupSettings(button);
					});
				else if (!is_from_auto_save)
					StatusMessageHandler.showError("Error trying to save new settings.\nPlease try again...");
				
				if (!is_from_auto_save)
					MyFancyPopup.hidePopup();
				else
					resetAutoSave();
			},
		});
	}
	else if (!is_from_auto_save)
		StatusMessageHandler.showMessage("Nothing to save.");
	else
		resetAutoSave();
}

function parseArray(html_elm) {
	var query_string = jsPlumbWorkFlow.jsPlumbProperty.getPropertiesQueryStringFromHtmlElm(html_elm[0], "task_property_field");
	var form_settings = {};
	parse_str(query_string, form_settings);
	
	return form_settings;
}
