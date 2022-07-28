$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
});

function loadSendEmailBlockSettings(settings_elm, settings_values) {
	loadEditSettingsBlockSettings(settings_elm, settings_values);
}
