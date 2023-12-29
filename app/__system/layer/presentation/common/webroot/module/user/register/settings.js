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

function loadRegisterBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var edit_settings = settings_elm.children(".edit_settings");
	var empty_settings_values = !settings_values || ($.isArray(settings_values) && settings_values.length == 0);
	var empty_settings_fields_values = empty_settings_values || !settings_values.hasOwnProperty("fields");
	
	if (empty_settings_values) {
		settings_values = {
			ptl: {
				code: '<div class="pr-2 small">' + "\n"
					+ '    <ptl:block:field:name/>' + "\n"
					+ '    <ptl:block:field:email/>' + "\n"
					+ '    ' + "\n"
					+ '    <ptl:block:field:username/>' + "\n"
					+ '    <ptl:block:field:password/>' + "\n"
					+ '    <ptl:block:field:security_question_1/>' + "\n"
					+ '    <ptl:block:field:security_answer_1/>' + "\n"
					+ '    <ptl:block:field:security_question_2/>' + "\n"
					+ '    <ptl:block:field:security_answer_2/>' + "\n"
					+ '    <ptl:block:field:security_question_3/>' + "\n"
					+ '    <ptl:block:field:security_answer_3/>' + "\n"
					+ '</div>' + "\n"
					+ '' + "\n"
					+ '<div class="buttons">' + "\n"
					+ '	<ptl:block:button:insert/>' + "\n"
					+ '</div>'
			},
			fields: {
				name: {
					field: {
						"class": "name form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-9"
						}
					}
				},
				email: {
					field: {
						"class": "email form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-9"
						}
					}
				},
				username: {
					field: {
						"class": "username form-group row pt-3 border-top mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-9"
						}
					}
				},
				password: {
					field: {
						"class": "password form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-9"
						}
					}
				},
				security_question_1: {
					field: {
						"class": "security_question_1 form-group row pt-3 border-top mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control form-select col-12 col-sm-8 col-lg-9"
						}
					}
				},
				security_answer_1: {
					field: {
						"class": "security_answer_1 form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-9"
						}
					}
				},
				security_question_2: {
					field: {
						"class": "security_question_2 form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control form-select col-12 col-sm-8 col-lg-9"
						}
					}
				},
				security_answer_2: {
					field: {
						"class": "security_answer_2 form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-9"
						}
					}
				},
				security_question_3: {
					field: {
						"class": "security_question_3 form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control form-select col-12 col-sm-8 col-lg-9"
						}
					}
				},
				security_answer_3: {
					field: {
						"class": "security_answer_3 form-group row mb-0",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-3"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-9"
						}
					}
				},
			},
			buttons: {
				insert: {
					field: {
						input: {
							"class": "btn btn-primary mt-3"
						}
					}
				}
			}
		};
	}
	
	var password_generator_elm = $('<div class="password_generator" title="Check this box to include a pasword generator."><label>Password Generator:</label><input type="checkbox" class="task_property_field" name="fields[password][field][input][password_generator]" value="1"></div>');
	edit_settings.find(".prop_password .form_containers .fields .field .input_settings .other_settings .allow_null").before(password_generator_elm);
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_user_types"),
		success: function(data) {
			if (data) {
				var options = '';
				$.each(data, function(index, user_type) {
					options += '<option value="' + user_type["user_type_id"] + '">' + user_type["name"] + '</option>';
				});
				
				var select = edit_settings.children(".user_type_id").children("select");
				select.html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load user types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadEditSettingsBlockSettings(settings_elm, settings_values, empty_settings_values ? {"remove": 0, "sort": 0} : null);
	loadUserEnvironmentsBlockSettings(edit_settings, settings_values, "user_environments");
	
	if (empty_settings_fields_values) {
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
			
			table.parent().closest(".input_options").children(".options_type").val("array").trigger("change");
		});
		
		//activate password generator by default
		password_generator_elm.children("input").attr("checked", "checked").prop("checked", true);
	}
	else {
		var tasks_values = convertBlockSettingsValuesIntoBasicArray(settings_values);
		
		//set password generator
		if (tasks_values && tasks_values.hasOwnProperty("fields") && tasks_values["fields"].hasOwnProperty("password") && tasks_values["fields"]["password"].hasOwnProperty("field") && tasks_values["fields"]["password"]["field"].hasOwnProperty("input") && tasks_values["fields"]["password"]["field"]["input"].hasOwnProperty("password_generator")) {
			var password_generator = tasks_values["fields"]["password"]["field"]["input"]["password_generator"];
			var input = password_generator_elm.children("input");
			
			if (input.val() == password_generator)
				input.attr("checked", "checked").prop("checked", true);
			else
				input.removeAttr("checked").prop("checked", false);
		}
		
		//set user type id
		if (tasks_values && tasks_values.hasOwnProperty("user_type_id") && tasks_values["user_type_id"]) {
			if (typeof tasks_values["user_type_id"] == "string")
				tasks_values["user_type_id"] = tasks_values["user_type_id"].split(",");
			
			edit_settings.find(".user_type_id select").val(tasks_values["user_type_id"]);
		}
	}
	
	MyFancyPopup.hidePopup();
}
