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
	var edit_settings = settings_elm.children(".edit_settings");
	var empty_settings_values = !settings_values || ($.isArray(settings_values) && settings_values.length == 0);
	
	if (empty_settings_values) {
		settings_values = {
			ptl: {
				code: '<div class="row small text-secondary ml-2 mr-2 ms-2 me-2">' + "\n"
					+ '    <div class="col-6 text-start text-left small">' + "\n"
					+ '        <ptl:block:field:label:tags/> <ptl:block:field:input:tags/>' + "\n"
					+ '    </div>' + "\n"
					+ '    <div class="col-6 text-end text-right small">Last updated <ptl:block:field:input:modified_date/></div>' + "\n"
					+ '</div>' + "\n"
					+ '<div class="card d-inline-block align-top border border-light rounded ml-2 mr-2 ms-2 me-2 p-0 shadow-sm text-start text-left w-100">' + "\n"
					+ ' 	<div class="card-img-top">' + "\n"
					+ ' 		<ptl:block:field:input:photo/>' + "\n"
					+ ' 	</div>' + "\n"
					+ ' 	<div class="card-body">' + "\n"
					+ ' 		<h5 class="card-title text-dark mb-0 text-capitalize">' + "\n"
					+ ' 			<ptl:block:field:input:title/>' + "\n"
					+ ' 		</h5>' + "\n"
					+ ' 		<div class="card-text text-secondary text-capitalize mt-1">' + "\n"
					+ ' 			<small class="text-body-secondary">' + "\n"
					+ ' 			    <ptl:block:field:input:sub_title/>' + "\n"
					+ ' 			</small>' + "\n"
					+ ' 		</div>' + "\n"
					+ ' 		<div class="card-text mt-2 text-capitalize">' + "\n"
					+ ' 			<ptl:block:field:input:summary/>' + "\n"
					+ ' 		</div>' + "\n"
					+ ' 		<div class="card-text mt-2 pt-3 border-top text-dark text-capitalize">' + "\n"
					+ '            <ptl:block:field:input:content/>' + "\n"
					+ '            <ptl:block:field:attachments/>' + "\n"
					+ ' 		</div>' + "\n"
					+ ' 	</div>' + "\n"
					+ '</div>'
			},
			fields: {
				photo: {
					field: {
						input: {
							"class": "w-100 rounded-top"
						}
					}
				},
			}
		};
	}
	
	loadEditSettingsBlockSettings(settings_elm, settings_values, empty_settings_values ? {"remove": 0, "sort": 0} : null);
	
	if (empty_settings_values) {
		//prepare fields with extra_attributes
		for (var field_id in settings_values["fields"]) {
			var input_extra_attributes = edit_settings.find(".prop_" + field_id + " > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_extra_attributes");
			
			if (input_extra_attributes.find(" > .attributes .task_property_field").length > 0)
				input_extra_attributes.find(" > .extra_attributes_type").val("array").trigger("change");
		}
	}
	
	//Prepare add_comment_url field
	var comments_field = edit_settings.children(".settings_props").children(".prop_comments").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field");
	
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
