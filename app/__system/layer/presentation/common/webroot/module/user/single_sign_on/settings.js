$(function () {
	var edit_settings = $(".edit_settings");
	
	edit_settings.find(".settings_prop:not(.extra_attribute) > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_type").hide();
	edit_settings.find(".settings_prop > .selected_task_properties > .form_containers > .fields > .field > .input_settings").children(".input_name, .input_value").remove();
	
	initObjectBlockSettings("edit_settings", saveSingleSignOn, "saveSingleSignOn");
	
	edit_settings.find(".prop_user_attachments").each(function (idx, elm) {
		$(elm).children(".settings_prop_default_value").remove();
		$(elm).find(".form_containers .fields .field").each(function (idx, field) {
			field = $(field);
			
			field.children(".disable_field_group").remove();
			field.children(".class").after( field.children(".label_settings").children(".label_value")[0] );
			field.children(".input_settings, .help_settings, .label_settings").remove();
		});
	});
	
	edit_settings.tabs();
	edit_settings.find(".user_settings .other_user_settings").tabs();
});

function loadSingleSignOnBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var edit_settings = settings_elm.children(".edit_settings");
	
	var password_generator_elm = $('<div class="password_generator" title="Check this box to include a pasword generator."><label>Password Generator:</label><input type="checkbox" class="task_property_field" name="fields[password][field][input][password_generator]" value="1"></div>');
	edit_settings.find(".prop_password .form_containers .fields .field .input_settings .other_settings .allow_null").before(password_generator_elm);
	
	//preparing default settings
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_user_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, user_type) {
					options += '<option value="' + user_type["user_type_id"] + '">' + user_type["name"] + '</option>';
				});
				
				var select = edit_settings.find(".user_type_id").children("select");
				select.html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load user types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	var user_settings = edit_settings.find(".user_settings");
	var other_user_settings = user_settings.find(".other_user_settings");
	
	loadObjectToObjectsBlockSettings(other_user_settings.find(".user_creation_settings"), settings_values, "object_to_objects");
	loadObjectBlockSettings(settings_elm, settings_values, "edit_settings");
	loadTaskFormFieldsBlockSettings(other_user_settings, settings_values, "user_creation_settings");
	loadTaskPTLFieldsBlockSettings(other_user_settings, settings_values, "user_creation_settings");
	onElsTabChange( other_user_settings.find(".els > .els_tabs > li")[0] );
	loadUserEnvironmentsBlockSettings(user_settings, settings_values, "user_environments");
	
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
	
	//prepare actions settings
	var actions_settings = edit_settings.find(".actions_settings");
	toggleValidationMessage(actions_settings.children(".validation_action").children("select")[0]);
	toggleNonValidationMessage(actions_settings.children(".non_validation_action").children("select")[0]);
	
	//prepare user settings
	var user_settings = edit_settings.find(".user_settings");
	onUserSettingsTypeChange( user_settings.find(".user_settings_type select")[0] );
	
	MyFancyPopup.hidePopup();
}

function onUserSettingsTypeChange(elm) {
	elm = $(elm);
	var type = elm.val();
	var p = elm.parent().closest(".user_settings");
	
	if (type == "relate_with_specific_user") {
		p.children(".user_id").show();
		p.children(".other_user_settings").hide();
	}
	else if (type == "create_new_user_if_none_found") {
		p.children(".user_id").hide();
		p.children(".other_user_settings").show();
	}
	else {
		p.children(".user_id, .other_user_settings").hide();
	}
}

function saveSingleSignOn(button) {
	var block_settings = $(".block_obj > .module_settings > .settings > .edit_settings");
	var actions_settings = block_settings.children(".actions_settings");
	
	if (!onSaveValidationFields(actions_settings))
		return false;
	
	saveEditSettings(button);
}
