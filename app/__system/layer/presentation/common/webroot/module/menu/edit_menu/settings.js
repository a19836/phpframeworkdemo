function onChangeGroupIdType(elm) {
	elm = $(elm);
	var p = elm.parent().parent();
	var value = elm.val();
	
	if (value == "manual_group_id") {
		p.children(".group_id").show();
		p.children(".group_id_by_tags, .group_id_from_related_object").hide();
	}
	else if (value == "first_group_id_by_tag_and" || value == "first_group_id_by_tag_or") {
		p.children(".group_id_by_tags").show();
		p.children(".group_id, .group_id_from_related_object").hide();
	}
	else if (value == "first_group_id_from_related_object") {
		p.children(".group_id, .group_id_by_tags").hide();
		p.children(".group_id_from_related_object").show();
		p.children(".group_id_from_related_object").children(".object_group").hide();
	}
	else if (value == "first_group_id_from_related_object_group") {
		p.children(".group_id, .group_id_by_tags").hide();
		p.children(".group_id_from_related_object").show();
		p.children(".group_id_from_related_object").children(".object_group").show();
	}
}

function loadEditMenuBlockSettings(settings_elm, settings_values) {
	var edit_settings = settings_elm.children(".edit_settings");
	var group_id_from_related_object = edit_settings.children(".group_id_from_related_object");
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_object_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				group_id_from_related_object.children(".object_type_id").children("select").html(options);
			}
			else {
				StatusMessageHandler.showError("There are no object types in the DB. Please create some menus first...");
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load object types from DB.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	if (!settings_values || $.isEmptyObject(settings_values)) {
		edit_settings.find(" > .style_type select").val(""); //set template style with this module css
	}
	else {
		var group_id_by_object = prepareBlockSettingsItemValue(settings_values["group_id_by_object"]);
		if (group_id_by_object) {
			group_id_from_related_object.children(".object_type_id").children("select").val( group_id_by_object["object_type_id"] );
			group_id_from_related_object.children(".object_id").children("input").val( group_id_by_object["object_id"] );
			group_id_from_related_object.children(".object_group").children("input").val( group_id_by_object["group"] );
		}
	}
	
	onChangeGroupIdType( settings_elm.children(".edit_settings").children(".group_id_type").children("select")[0] );
}
