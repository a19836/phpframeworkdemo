/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$(function () {
	//Fixing username input_value issue
	$(".prop_username").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field").children(".input_settings").children(".input_name, .input_value").children("input").each(function(idx, elm) {
		elm = $(elm);
		
		var v = elm.val().replace("[username]", "[user_id]");
		elm.val(v);
	});
});
