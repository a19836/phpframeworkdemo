/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

function loadEditUserUserTypeSettingsBlockSettings(settings_elm, settings_values) {
	settings_elm.find(".status_action_update .on_ok_action select").val("alert_message_and_redirect");
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
}
