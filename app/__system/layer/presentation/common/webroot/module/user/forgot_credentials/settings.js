function loadForgotCredentialsBlockSettings(settings_elm, settings_values) {
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	loadUserEnvironmentsBlockSettings(settings_elm.children(".edit_settings"), settings_values, "user_environments");
}
