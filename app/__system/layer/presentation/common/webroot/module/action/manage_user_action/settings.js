$(function () {
	initObjectBlockSettings("manage_user_action_settings", saveManageUserAction, "saveManageUserAction");
});

function loadManageUserActionBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".manage_user_action_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_data"),
		success: function(data) {
			if (data) {
				var object_types = data["object_types"];
				var actions = data["actions"];
				
				var options = '';
				$.each(object_types, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.children(".object_type_id").children("select").html(options);
				
				options = '';
				$.each(actions, function(index, action) {
					options += '<option value="' + action["action_id"] + '">' + action["name"] + '</option>';
				});
				block_settings.children(".action_id").children("select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load data.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadObjectBlockSettings(settings_elm, settings_values, "manage_user_action_settings");
}

function saveManageUserAction(button) {
	saveObjectBlock(button, "manage_user_action_settings");
}
