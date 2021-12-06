$(function () {
	initObjectBlockSettings("show_object_comments_settings", saveShowObjectComments, "saveShowObjectComments");
});

function loadShowObjectCommentsBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".show_object_comments_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.children(".object_type_id").children("select").html(options);
				block_settings.children(".filter_by_parent").children(".filter_object_type_id").children("select").html('<option></option>' + options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load object types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadObjectBlockSettings(settings_elm, settings_values, "show_object_comments_settings");
	
	//Load Filter settings
	var filter_settings = settings_values ? prepareBlockSettingsItemValue(settings_values["filter_by_parent"]) : null;
	if (!jQuery.isEmptyObject(filter_settings)) {
		var filter_by_parent_elm = block_settings.children(".filter_by_parent");
		filter_by_parent_elm.find(".filter_object_type_id").first().children("select").val(filter_settings["object_type_id"]);
		filter_by_parent_elm.find(".filter_object_id").first().children("input").val(filter_settings["object_id"]);
		filter_by_parent_elm.find(".filter_group").first().children("input").val(filter_settings["group"]);
	}
	
	toggleAddCommentUrl( block_settings.children(".show_add_comment").children("input")[0] );
	onChangeFilter( block_settings.children(".filter").children("select")[0] );
}

function onChangeFilter(elm) {
	elm = $(elm);
	var value = elm.val();
	var filter_by_parent = elm.parent().parent().children(".filter_by_parent");
	
	if (value == "filter_by_parent") {
		filter_by_parent.show();
		filter_by_parent.children(".filter_group").hide();
	}
	else if (value == "filter_by_parent_group") {
		filter_by_parent.show();
		filter_by_parent.children(".filter_group").show();
	}
	else 
		filter_by_parent.hide();
}

function toggleAddCommentUrl(elm) {
	elm = $(elm);
	
	if (elm.is(":checked"))
		elm.parent().parent().children(".add_comment_url").show();
	else
		elm.parent().parent().children(".add_comment_url").hide();
}

function saveShowObjectComments(button) {
	saveObjectBlock(button, "show_object_comments_settings");
}
