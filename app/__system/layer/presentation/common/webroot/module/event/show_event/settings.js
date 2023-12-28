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

function loadEventPropertiesBlockSettings(settings_elm, settings_values) {
	var edit_settings = settings_elm.children(".edit_settings");
	var empty_settings_values = !settings_values || ($.isArray(settings_values) && settings_values.length == 0);
	
	if (empty_settings_values) {
		settings_values = {
			ptl: {
				code: '<div class="card d-inline-block align-top border border-light rounded m-2 p-4 w-100 shadow-sm text-start text-left">' + "\n"
					+ '	<div class="row">' + "\n"
					+ '		<div class="col-2 text-center text-danger">' + "\n"
					+ '			<div class="small text-secondary">' + "\n"
					+ '			    <span class="glyphicon glyphicon-calendar fas fa-fw fa-calendar"></span>' + "\n"
					+ '			</div>' + "\n"
					+ '			<div class="fs-3 h3 mt-1 mb-0 font-weight-bold">' + "\n"
					+ '				<ptl:block:field:value:begin_day/>' + "\n"
					+ '			</div>' + "\n"
					+ '			<div class="text-uppercase">' + "\n"
					+ '				<ptl:block:field:value:begin_month_short_text/>' + "\n"
					+ '			</div>' + "\n"
					+ '		</div>' + "\n"
					+ '		<div class="col-10">' + "\n"
					+ '			<div class="small text-secondary">' + "\n"
					+ '				<span class="glyphicon glyphicon-calendar fas fa-fw fa-clock"></span>' + "\n"
					+ '				<ptl:block:field:value:begin_time/> - <ptl:block:field:value:end_time/>' + "\n"
					+ '			</div>' + "\n"
					+ '			<div class="text-dark mt-1 text-capitalize">' + "\n"
					+ '				<ptl:block:field:input:title/>' + "\n"
					+ '			</div>' + "\n"
					+ '        	<div class="text-secondary small mt-1 text-capitalize">' + "\n"
					+ '        		<ptl:block:field:sub_title/>' + "\n"
					+ '        	</div>' + "\n"
					+ '		</div>' + "\n"
					+ '	</div>' + "\n"
					+ '	<div class="mt-3">' + "\n"
					+ '		<ptl:block:field:photo/>' + "\n"
					+ '	</div>' + "\n"
					+ '	<div class="mt-2 mb-3 small text-center">' + "\n"
					+ '        <ptl:block:field:date_interval/>' + "\n"
					+ '        <ptl:block:field:location/>' + "\n"
					+ '	</div>' + "\n"
					+ '	<div class="text-dark small text-capitalize pt-3 border-top">' + "\n"
					+ '        <ptl:block:field:description/>' + "\n"
					+ '        <ptl:block:field:attachments/>' + "\n"
					+ '	</div>' + "\n"
					+ '	<div class="small text-center mt-3">' + "\n"
					+ '		<span class="glyphicon glyphicon-user fas fa-fw fa-user align-middle"></span>' + "\n"
					+ '		<span class="small">' + "\n"
					+ '			By <ptl:block:field:input:user/>' + "\n"
					+ '		</span>' + "\n"
					+ '	</div>' + "\n"
					+ '</div>'
			},
			fields: {
				photo: {
					field: {
						input: {
							"class": "card-img-top rounded"
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
			'<input class="task_property_field module_settings_property" type="text" value="" name="fields[comments][field][add_comment_url]" url_suffix="?event_id=">' +
			'<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>' +
			'<div class="info">The system will add the event id at the end of the url. The url must be an ajax request which returns <status>1</status> on success.</div>' +
		'</div>' +
		'<div class="clear"></div>';
	
		comments_field.append(html);
	
		var fields = prepareBlockSettingsItemValue(settings_values["fields"]);
		
		if (fields && fields["comments"] && fields["comments"]["field"] && fields["comments"]["field"]["add_comment_url"]) {
			comments_field.children(".add_comment_url").children("input").val( fields["comments"]["field"]["add_comment_url"] );
		}
	}
}
