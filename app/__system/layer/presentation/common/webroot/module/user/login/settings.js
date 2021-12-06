function loadLoginBlockSettings(settings_elm, settings_values) {
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	loadUserEnvironmentsBlockSettings(settings_elm.children(".edit_settings"), settings_values, "user_environments");
	
	togglePanelFromCheckbox( settings_elm.find(".login_settings .show_captcha input")[0], "maximum_login_attempts_to_show_captcha" );
}

function onUserLoginUpdatePTLFromFieldsSettings(elm, settings, code, external_vars) {
	code += "\n<ptl:block:field:captcha/>\n<ptl:block:button:submit/>\n<ptl:block:button:forgot-credentials/>\n<ptl:block:button:register/>\n<ptl:block:button:single-sign-on/>";
	
	return code;
}
