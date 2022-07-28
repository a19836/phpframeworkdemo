var object_type_page_id = -1;

$(function () {
	initObjectBlockSettings("validate_object_to_object_settings", saveValidateUserActivity, "saveValidateUserActivity");
});

function loadValidateUserActivityBlockSettings(settings_elm, settings_values) {
	var block_settings = settings_elm.children(".validate_object_to_object_settings");
	
	var module_object_id = block_settings.children(".module_object_id");
	var input = module_object_id.children("input");
	input.after('<select class="module_settings_property" name="activity_id"></select>');
	input.remove();
	module_object_id.children("span").remove();
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_activities"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, activity) {
					options += '<option value="' + activity["activity_id"] + '">' + activity["name"] + '</option>';
				});
				block_settings.children(".module_object_id").children("select").html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load data.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadValidateObjectToObjectBlockSettings(settings_elm, settings_values);
	
	//Prepare object_type_id select
	var select = block_settings.children(".object_type_id").children("select");
	if (select[0]) {
		select.attr("onChange", "toggleObjectIdPanel(this)");
		
		var options = select[0].options;
		for (var i = 0; i < options.length; i++) {
			if (options[i].text == "page") {
				object_type_page_id = options[i].value;
				break;
			}
		}
		
		select.prepend('<option value="current_page">Current Page</option>');
		
		if (select.val() == object_type_page_id && block_settings.children(".object_id").children("input").val() == "$entity_path") {
			select.val("current_page");
		}
		
		toggleObjectIdPanel(select[0]);
	}
}

function toggleObjectIdPanel(elm) {
	elm = $(elm);
	
	if (elm.val() == "current_page") {
		elm.parent().parent().children(".object_id").hide().children("input").val("$entity_path");
	}
	else {
		var panel = elm.parent().parent().children(".object_id");
		togglePageLevel(panel.children("span").children("input")[0]);
		panel.show();
	}
}

function saveValidateUserActivity(button) {
	var block_settings = $(".block_obj > .module_settings > .settings > .validate_object_to_object_settings");
	
	var select = block_settings.children(".object_type_id").children("select");
	var cp = select.val() == "current_page" && object_type_page_id != -1;
	if (cp) {
		select.val(object_type_page_id);
	}
	
	saveValidateObjectToObject(button);
	
	if (cp) {
		select.val("current_page");
	}
}
