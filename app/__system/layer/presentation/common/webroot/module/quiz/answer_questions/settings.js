$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	settings_prop.children(".settings_prop_default_value").remove();
	//$(".edit_settings .ptl_default_values").remove(); //20190608 Not sure for what this is for. I believe this was for something old, that got forgothen here and now is not needed anymore.
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
});

function loadAnswerQuestionsBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".edit_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.children(".get_by_parent").children(".questions_parent_object_type_id").children("select").html('<option></option>' + options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load object types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	onChangeQuestionsType( block_settings.children(".questions_type").children("select")[0] );
}

function onUserAnswersUpdatePTLFromFieldsSettings(elm, settings, code, external_vars) {
	//cleans default fields
	code = code.replace(/<ptl:block:field:([a-z_]+)\/>/gi, "");
	
	//adds answers
	code = "<ptl:block:field:questions_answers/>\n" + code;
	
	return code;
}

function onChangeQuestionsType(elm) {
	elm = $(elm);
	var value = elm.val();
	var p = elm.parent().parent();
	var get_by_parent = p.children(".get_by_parent");
	
	if (value == "get_by_parent") {
		get_by_parent.show();
		get_by_parent.children(".questions_parent_group").hide();
	}
	else if (value == "get_by_parent_group") {
		get_by_parent.show();
		get_by_parent.children(".questions_parent_group").show();
	}
	else
		get_by_parent.hide();
}
