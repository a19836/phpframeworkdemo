$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	settings_prop.children(".settings_prop_default_value").remove();
	//$(".edit_settings .ptl_default_values").remove(); //20190608 Not sure for what this is for. I believe this was for something old, that got forgothen here and now is not needed anymore.
	
	fields.children(".input_settings").children(".input_name").hide();
	
	settings_prop.filter(".prop_attachments, .prop_comments").children(".selected_task_properties").find(".fields .field").each(function (idx, field) {
		field = $(field);
		
		field.children(".class").after( field.children(".label_settings").children(".label_value")[0] );
		field.children(".label_settings, .input_settings, .help_settings").remove();
	});
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
});

function loadArticlePropertiesBlockSettings(settings_elm, settings_values) {
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	
	//Prepare add_comment_url field
	var comments_field = settings_elm.children(".edit_settings").children(".settings_props").children(".prop_comments").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field");
	
	if (comments_field[0]) {
		var html = '<div class="add_comment_url">' +
			'<label>Add new comment url:</label>' +
			'<input class="task_property_field module_settings_property" type="text" value="" name="fields[comments][field][add_comment_url]" url_suffix="?article_id=">' +
			'<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>' +
			'<div class="info">The system will add the article id at the end of the url. The url must be an ajax request which returns <status>1</status> on success.</div>' +
		'</div>' +
		'<div class="clear"></div>';
	
		comments_field.append(html);
	
		var fields = prepareBlockSettingsItemValue(settings_values["fields"]);
		
		if (fields && fields["comments"] && fields["comments"]["field"] && fields["comments"]["field"]["add_comment_url"]) {
			comments_field.children(".add_comment_url").children("input").val( fields["comments"]["field"]["add_comment_url"] );
		}
	}
}
