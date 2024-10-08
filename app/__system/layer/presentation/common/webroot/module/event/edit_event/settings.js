$(function () {
	$(".settings_prop.prop_title, .settings_prop.prop_sub_title, .settings_prop.prop_tags, .settings_prop.prop_description, .settings_prop.prop_address, .settings_prop.prop_zip_id, .settings_prop.prop_locality, .settings_prop.prop_latitude, .settings_prop.prop_longitude").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field").children(".input_settings").children(".input_type").show();
	
	$(".settings_prop.prop_photo_id .selected_task_properties").find(".disable_field_group, .label_settings .label_type, .label_settings .label_title").remove();
	
	$(".edit_settings .prop_event_attachments, .edit_settings .prop_map").each(function (idx, elm) {
		$(elm).children(".settings_prop_default_value").remove();
		$(elm).find(".form_containers .fields .field").each(function (idx, field) {
			field = $(field);
			
			field.children(".disable_field_group").remove();
			field.children(".class").after( field.children(".label_settings").children(".label_value")[0] );
			field.children(".input_settings, .help_settings, .label_settings").remove();
		});
	});
});
