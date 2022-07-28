$(function () {
	$(".top_bar .save a").attr("onclick", "saveLogout(this);");
});

function loadLogoutBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	var edit_settings = settings_elm.children(".edit_settings");
	var validate_object_to_object_settings = edit_settings.find(".validate_object_to_object_settings");
	var task_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
	
	//for old code which add "message" and "block_class" fields
	if (task_values.hasOwnProperty("message") || task_values.hasOwnProperty("block_class")) {
		var select = validate_object_to_object_settings.find(".validation_action select");
		select.val("show_message");
		
		if (task_values.hasOwnProperty("message"))
			validate_object_to_object_settings.find(".validation_message input").val( task_values["message"] );
		
		if (task_values.hasOwnProperty("block_class"))
			validate_object_to_object_settings.find(".validation_class input").val( task_values["block_class"] );
	}
	
	//prepare actions settings
	toggleValidationMessage(validate_object_to_object_settings.children(".validation_action").children("select")[0]);
	toggleNonValidationMessage(validate_object_to_object_settings.children(".non_validation_action").children("select")[0]);
	
	MyFancyPopup.hidePopup();
}

function saveLogout(button) {
	var block_settings = $(".block_obj > .module_settings > .settings > .edit_settings");
	var validate_object_to_object_settings = block_settings.children(".validate_object_to_object_settings");
	
	if (!onSaveValidationFields(validate_object_to_object_settings))
		return false;
	
	saveEditSettings(button);
}
