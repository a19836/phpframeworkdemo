function loadValidateObjectToObjectBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".validate_object_to_object_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.children(".object_type_id").children("select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load data.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadObjectBlockSettings(settings_elm, settings_values, "validate_object_to_object_settings");
	
	toggleValidationTypeCondition(block_settings.children(".validation_condition").children("select")[0]);
	
	togglePageLevel(block_settings.children(".module_object_id").children("span").children("input")[0]);
	togglePageLevel(block_settings.children(".object_id").children("span").children("input")[0]);
	
	toggleValidationMessage(block_settings.children(".validation_action").children("select")[0]);
	toggleNonValidationMessage(block_settings.children(".non_validation_action").children("select")[0]);
}

function toggleValidationTypeCondition(elm) {
	elm = $(elm);
	
	if (elm.val() == "with_condition")
		elm.parent().children("input").show();
	else
		elm.parent().children("input").hide();
}

function togglePageLevel(elm) {
	if (elm) {
		elm = $(elm);
		var input = elm.parent().parent().children("input");
	
		var name = input.attr("name").replace(/_/g, " ").toLowerCase();
		name = name.charAt(0).toUpperCase() + name.slice(1);
	
		if (elm.is(":checked")) {
			var value = input.val();
			input.attr("disabled", "disabled").val("$block_local_variables['" + name + "']").addClass("input_disabled").attr("old_value", value);
		}
		else {
			input.removeAttr("disabled").removeClass("input_disabled");
		
			if (input.val() == "$block_local_variables['" + name + "']") {
				input.val( input[0].hasAttribute("old_value") ? input.attr("old_value") : "" );
			}
		}
	}
}

function toggleValidationMessage(elm) {
	toggleMessage(elm, 'validation_message', 'validation_class', 'validation_redirect', 'validation_ttl', 'validation_blocks_execution');
}

function toggleNonValidationMessage(elm) {
	toggleMessage(elm, 'non_validation_message', 'non_validation_class', 'non_validation_redirect', 'non_validation_ttl', 'non_validation_blocks_execution');
}

function toggleMessage(elm, msg_class_name, msg_class_class_name, redirect_class_name, ttl_class_name, blocks_execution_class_name) {
	if (elm) {
		elm = $(elm);
	
		var name = elm.attr("name");
		var value = elm.val();
		var block_settings = elm.parent().parent();
		
		switch (value) {
			case "show_message": 
				block_settings.children("." + msg_class_name + ", ." + msg_class_class_name + ", ." + blocks_execution_class_name).show();
				block_settings.children("." + redirect_class_name + ", ." + ttl_class_name).hide();
				break;
			case "show_message_and_redirect": 
				block_settings.children("." + msg_class_name + ", ." + msg_class_class_name + ", ." + redirect_class_name + ", ." + ttl_class_name + ", ." + blocks_execution_class_name).show();
				break;
			case "alert_message": 
				block_settings.children("." + msg_class_name + ", ." + blocks_execution_class_name).show();
				block_settings.children("." + msg_class_class_name + ", ." + redirect_class_name + ", ." + ttl_class_name).hide();
				break;
			case "alert_message_and_redirect": 
				block_settings.children("." + msg_class_name + ", ." + redirect_class_name).show();
				block_settings.children("." + msg_class_class_name + ", ." + ttl_class_name + ", ." + blocks_execution_class_name).hide();
				break;
			case "redirect":
				block_settings.children("." + redirect_class_name + ", ." + ttl_class_name).show();
				block_settings.children("." + msg_class_name + ", ." + msg_class_class_name + ", ." + blocks_execution_class_name).hide();
				break;
			case "alert_message_and_die": 
				block_settings.children("." + msg_class_name).show();
				block_settings.children("." + msg_class_class_name + ", ." + redirect_class_name + ", ." + ttl_class_name + ", ." + blocks_execution_class_name).hide();
				break;
			case "die": 
				block_settings.children("." + msg_class_name + ", ." + msg_class_class_name).show();
				block_settings.children("." + redirect_class_name + ", ." + ttl_class_name + ", ." + blocks_execution_class_name).hide();
				break;
			default: 
				block_settings.children("." + msg_class_name + ", ." + msg_class_class_name + ", ." + redirect_class_name + ", ." + ttl_class_name + ", ." + blocks_execution_class_name).hide();
				
				if (value == "")
					block_settings.children("." + msg_class_class_name).show();
		}
	}
}

function onSaveValidationFields(block_settings) {
	var action = block_settings.children(".validation_action").children("select").val();
	if (action == "show_message_and_redirect" || action == "alert_message_and_redirect" || action == "redirect") {
		var redirect = block_settings.children(".validation_redirect").children("input").val();
		if (redirect.replace(/\s+/g, "") == "") {
			StatusMessageHandler.showError("Validation Redirect URL cannot be undefined.\nPlease try again...");
			return false;
		}
	}
	
	var action = block_settings.children(".non_validation_action").children("select").val();
	if (action == "show_message_and_redirect" || action == "alert_message_and_redirect" || action == "redirect") {
		var redirect = block_settings.children(".non_validation_redirect").children("input").val();
		if (redirect.replace(/\s+/g, "") == "") {
			StatusMessageHandler.showError("Non-Validation Redirect URL cannot be undefined.\nPlease try again...");
			return false;
		}
	}
	
	return true;
}

function saveValidateObjectToObject(button) {
	var block_settings = $(button).parent().parent().children(".module_settings").children(".settings").children(".validate_object_to_object_settings");
	
	//Only if exists
	var module_object_id = block_settings.children(".module_object_id").children("input");
	if (module_object_id[0] && module_object_id.val().replace(/\s+/g, "") == "") {
		var name = module_object_id.attr("name").replace(/_/g, " ").toLowerCase();
		name = name.charAt(0).toUpperCase() + name.slice(1);
		StatusMessageHandler.showError(name + " cannot be undefined.\nPlease try again...");
		return false;
	}
	
	var object_id = block_settings.children(".object_id").children("input").val();
	if (object_id.replace(/\s+/g, "") == "") {
		StatusMessageHandler.showError("Object Id cannot be undefined.\nPlease try again...");
		return false;
	}
	
	if (!onSaveValidationFields(block_settings))
		return false;
	
	saveObjectBlock(button, "validate_object_to_object_settings");
}
