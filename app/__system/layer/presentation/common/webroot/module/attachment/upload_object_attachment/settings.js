$(function () {
	initObjectBlockSettings("upload_object_attachment_settings", saveUploadObjectAttachment, "saveUploadObjectAttachment");
});

function loadUploadObjectAttachmentBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var block_settings = settings_elm.children(".upload_object_attachment_settings");
	
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
	
	loadObjectBlockSettings(settings_elm, settings_values, "upload_object_attachment_settings");
	
	MyFancyPopup.hidePopup();
}

function saveUploadObjectAttachment(button) {
	saveObjectBlock(button, "upload_object_attachment_settings");
}
