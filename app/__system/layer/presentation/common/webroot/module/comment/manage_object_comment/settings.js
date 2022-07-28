$(function () {
	initObjectBlockSettings("manage_object_comment_settings", saveManageObjectComment, "saveManageObjectComment");
	
	var manage_object_comment_settings = $(".manage_object_comment_settings");
	manage_object_comment_settings.children(".allow_view, .status_action_insert, .button_settings_insert, .status_action_update, .button_settings_update, .status_action_delete, .button_settings_delete, .undefined_object, .status_action_undefined_object").remove();
	manage_object_comment_settings.children(".allow_insertion, .allow_update, .allow_deletion").children(".icon").remove();
});

function loadManageObjectCommentBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".manage_object_comment_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.children(".object_type_id").children("select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load object types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
}

function saveManageObjectComment(button) {
	saveObjectBlock(button, "manage_object_comment_settings");
}
