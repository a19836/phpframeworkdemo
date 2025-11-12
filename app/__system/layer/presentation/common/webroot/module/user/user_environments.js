/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

function loadUserEnvironmentsBlockSettings(parent_elm, settings_values, class_name) {
	class_name = class_name ? class_name : "user_environments";
	
	var user_environments_elm = parent_elm.children("." + class_name);
	
	if (user_environments_elm[0]) {
		var user_environments_items = settings_values ? prepareBlockSettingsItemValue(settings_values[class_name]) : null;
		
		if (!jQuery.isEmptyObject(user_environments_items)) {
			var add_icon = user_environments_elm.children(".add");
			var user_environment_elms = user_environments_elm.children(".user_environment");
			eval ('var user_environment_html = ' + class_name + '_html;');
			var elms_idx = 0;
			
			$.each(user_environments_items, function(idx, value) {
				var item = user_environments_items[idx];
				var o2o_elm = user_environment_elms[elms_idx];
				
				if (!o2o_elm) 
					o2o_elm = addUserEnvironmentItem(add_icon[0], user_environment_html);
				else
					o2o_elm = $(o2o_elm);
				
				o2o_elm.children(".environment_id").children("input").val(item);
				
				elms_idx++;
			});
		}
	}
}

function addUserEnvironmentItem(elm, user_environment_html) {
	var o2o_elm = $(user_environment_html);
	$(elm).parent().append(o2o_elm);
	
	return o2o_elm;
}
