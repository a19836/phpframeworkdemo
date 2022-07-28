$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	settings_prop.children(".settings_prop_default_value").remove();
	//$(".edit_settings .ptl_default_values").remove(); //20190608 Not sure for what this is for. I believe this was for something old, that got forgothen here and now is not needed anymore.
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("list_settings", saveListSettings, "saveListSettings");
});

function loadListQuestionsBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".list_settings");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				block_settings.children(".list_by_parent").children(".list_parent_object_type_id").children("select").html('<option></option>' + options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load object types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadListSettingsBlockSettings(settings_elm, settings_values);
	
	onChangeQuestionsType( block_settings.find(".questions_type select")[0] );
}

function onChangeQuestionsType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var list_settings = elm.parent().parent();
	
	list_settings.children(".list_by_parent").hide();
	list_settings.find(".list_by_parent > .list_parent_group").hide();
	
	if (value == "parent") {
		list_settings.children(".list_by_parent").show();
	}
	else if (value == "parent_group") {
		var cp = list_settings.children(".list_by_parent");
		cp.show();
		cp.children(".list_parent_group").show();
	}
}
