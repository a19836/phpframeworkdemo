function loadLoginBlockSettings(settings_elm, settings_values) {
	var edit_settings = settings_elm.children(".edit_settings");
	var empty_settings_values = !settings_values || ($.isArray(settings_values) && settings_values.length == 0);
	
	if (empty_settings_values) {
		settings_values = {
			ptl: {
				code: '<ptl:block:field:username/>' + "\n"
					+ '<ptl:block:field:password/>' + "\n"
					+ '<ptl:block:field:captcha/>' + "\n"
					+ '<div class="buttons mb-1">' + "\n"
					+ '	<div class="submit_button button">' + "\n"
					+ '		<input class="btn btn-danger" value="<ptl:echo translateProjectText(\\$EVC, "Login") />" type="submit" name="login"/>' + "\n"
					+ '	</div>' + "\n"
					+ '</div>' + "\n"
					+ '' + "\n"
					+ '<ptl:block:button:forgot-credentials/>' + "\n"
					+ '<ptl:block:button:register/>' + "\n"
					+ '<ptl:block:button:single-sign-on/>'
			},
			fields: {
				username: {
					field: {
						label: {
							"class": "form-label mb-0 small",
							next_html: '<i class="text-danger">*</i>'
						},
						input: {
							"class": "form-control"
						}
					}
				},
				password: {
					field: {
						"class": "password mt-4 mb-4",
						label: {
							"class": "form-label mb-0 small",
							next_html: '<i class="text-danger">*</i>'
						},
						input: {
							"class": "form-control"
						}
					}
				}
			}
		};
	}
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	loadUserEnvironmentsBlockSettings(edit_settings, settings_values, "user_environments");
	
	togglePanelFromCheckbox( settings_elm.find(".login_settings .show_captcha input")[0], "maximum_login_attempts_to_show_captcha" );
}

function onUserLoginUpdatePTLFromFieldsSettings(elm, settings, code, external_vars) {
	code += "\n<ptl:block:field:captcha/>\n<ptl:block:button:submit/>\n<ptl:block:button:forgot-credentials/>\n<ptl:block:button:register/>\n<ptl:block:button:single-sign-on/>";
	
	return code;
}
