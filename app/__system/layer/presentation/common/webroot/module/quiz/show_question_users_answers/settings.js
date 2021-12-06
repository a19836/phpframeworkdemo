$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	settings_prop.children(".settings_prop_default_value").remove();
	//$(".edit_settings .ptl_default_values").remove(); //20190608 Not sure for what this is for. I believe this was for something old, that got forgothen here and now is not needed anymore.
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
});

function loadShowQuestionUsersAnswersBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".edit_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.children(".get_next_by_parent").children(".get_next_object_type_id").children("select").html('<option></option>' + options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load object types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	//Load get next settings
	var get_next_settings = settings_values ? prepareBlockSettingsItemValue(settings_values["get_next_by_parent"]) : null;
	if (!jQuery.isEmptyObject(get_next_settings)) {
		var get_next_by_parent_elm = block_settings.children(".get_next_by_parent");
		get_next_by_parent_elm.find(".get_next_object_type_id").first().children("select").val(get_next_settings["object_type_id"]);
		get_next_by_parent_elm.find(".get_next_object_id").first().children("input").val(get_next_settings["object_id"]);
		get_next_by_parent_elm.find(".get_next_group").first().children("input").val(get_next_settings["group"]);
	}
	
	onChangeQuestionType( block_settings.children(".question_type").children("select")[0] );
}

function onChangeQuestionType(elm) {
	elm = $(elm);
	var value = elm.val();
	var p = elm.parent().parent();
	var get_next_by_parent = p.children(".get_next_by_parent");
	var question_id = p.children(".question_id");
	
	if (value == "get_next_by_parent") {
		get_next_by_parent.show();
		get_next_by_parent.children(".get_next_group").hide();
		question_id.hide();
	}
	else if (value == "get_next_by_parent_group") {
		get_next_by_parent.show();
		get_next_by_parent.children(".get_next_group").show();
		question_id.hide();
	}
	else {
		get_next_by_parent.hide();
		question_id.show();
	}
}
