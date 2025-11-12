/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$(function () {
	$(".settings_prop:not(.extra_attribute) > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_type").hide();
	$(".settings_prop > .selected_task_properties > .form_containers > .fields > .field > .input_settings").children(".input_name, .input_value").remove();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
});
