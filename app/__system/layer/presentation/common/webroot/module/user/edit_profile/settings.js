$(function () {
	$(".edit_settings .prop_user_attachments").each(function (idx, elm) {
		$(elm).children(".settings_prop_default_value").remove();
		$(elm).find(".form_containers .fields .field").each(function (idx, field) {
			field = $(field);
			
			field.children(".disable_field_group").remove();
			field.children(".class").after( field.children(".label_settings").children(".label_value")[0] );
			field.children(".input_settings, .help_settings, .label_settings").remove();
		});
	});
});

function loadEditProfileBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var edit_settings = settings_elm.children(".edit_settings");
	
	var password_generator_elm = $('<div class="password_generator" title="Check this box to include a pasword generator."><label>Password Generator:</label><input type="checkbox" class="task_property_field" name="fields[password][field][input][password_generator]" value="1"></div>');
	edit_settings.find(".prop_password .form_containers .fields .field .input_settings .other_settings .allow_null").before(password_generator_elm);
	
	loadEditSettingsBlockSettings(settings_elm, settings_values);
	loadUserEnvironmentsBlockSettings(settings_elm.children(".edit_settings"), settings_values, "user_environments");
	
	if (!settings_values || !settings_values.hasOwnProperty("fields")) {
		//set default questions
		var questions = ["What is the first name of the person you first kissed?", "What is the last name of the teacher who gave you your first failing grade?", "What is the name of the place your wedding reception was held?", "What was the name of your elementary / primary school?", "In what city or town does your nearest sibling live?", "What time of the day were you born? (hh:mm)"];
		
		edit_settings.find(".prop_security_question_1, .prop_security_question_2, .prop_security_question_3").children(".selected_task_properties").children(".form_containers").children(".fields").children(".field").children(".input_settings").children(".input_options").children("table").each(function(idx, table) {
			table = $(table);
			var icon = table.find("th.icons .add");
		
			for (var i = 0; i < questions.length; i++) {
				icon.click();
				var tr = table.find("tr").last();
				tr.children("td.label, td.value").children("input").val( questions[i] );
			}
		});
		
		//activate password generator by default
		password_generator_elm.children("input").attr("checked", "checked").prop("checked", true);
	}
	else {
		var tasks_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
		
		if (tasks_values && tasks_values.hasOwnProperty("fields") && tasks_values["fields"].hasOwnProperty("password") && tasks_values["fields"]["password"].hasOwnProperty("field") && tasks_values["fields"]["password"]["field"].hasOwnProperty("input") && tasks_values["fields"]["password"]["field"]["input"].hasOwnProperty("password_generator")) {
			var password_generator = tasks_values["fields"]["password"]["field"]["input"]["password_generator"];
			var input = password_generator_elm.children("input");
			
			if (input.val() == password_generator)
				input.attr("checked", "checked").prop("checked", true);
			else
				input.removeAttr("checked").prop("checked", false);
		}
	}
	
	MyFancyPopup.hidePopup();
}
