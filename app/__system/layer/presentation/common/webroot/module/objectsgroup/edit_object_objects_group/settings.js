function loadEditObjectObjectsGroupSettingsBlockSettings(settings_elm, settings_values) {
	settings_elm.find(".status_action_update .on_ok_action select").val("alert_message_and_redirect");
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
}
