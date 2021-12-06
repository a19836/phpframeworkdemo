$(function () {
	$(".settings_prop:not(.extra_attribute) > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_type").hide();
	$(".settings_prop > .selected_task_properties > .form_containers > .fields > .field > .input_settings").children(".input_name, .input_value").remove();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
});
