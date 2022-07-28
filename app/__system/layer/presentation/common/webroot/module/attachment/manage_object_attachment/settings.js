$(function () {
	initObjectBlockSettings("manage_object_attachment_settings", saveManageObjectAttachment, "saveManageObjectAttachment");
});

function loadManageObjectAttachmentBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".manage_object_attachment_settings");
	
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
	
	onChangeManageObjectAttachmentAction( block_settings.find(".action select")[0] );
}

function onChangeManageObjectAttachmentAction(elm) {
	elm = $(elm);
	var p = elm.parent().parent();
	var value = elm.val();
	
	p.children(".file_variable, .resize_width, .resize_height").hide();
		
	if (value == "upload") 
		p.children(".file_variable").show();
	else if (value == "image_upload_resize")
		p.children(".file_variable, .resize_width, .resize_height").show();
}

function saveManageObjectAttachment(button) {
	saveObjectBlock(button, "manage_object_attachment_settings");
}
