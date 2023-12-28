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
	
	$(".edit_settings .prop_active > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_type").show().find("select option").each(function (idx, option) {
		option = $(option);
		var option_value = option.val();
		
		if (option_value != "select" && option_value != "checkbox")
			option.remove();
	});
});

function loadEditUserBlockSettings(settings_elm, settings_values) {
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
				code: '<div class="row">' + "\n"
					+ '    <div class="cold-12 col-sm-6">' + "\n"
					+ '        <div class="h6 font-weight-bold mt-4 mb-2"><ptl:echo translateProjectText(\\$EVC, "User Details")/></div>' + "\n"
					+ '        ' + "\n"
					+ '        <div class="card">' + "\n"
					+ '            <div class="card-body">' + "\n"
					+ '                <ptl:block:field:active/>' + "\n"
					+ '                <ptl:block:field:name/>' + "\n"
					+ '                <ptl:block:field:email/>' + "\n"
					+ '            </div>' + "\n"
					+ '        </div>' + "\n"
					+ '    </div>' + "\n"
					+ '    ' + "\n"
					+ '    <div class="cold-12 col-sm-6 user_type_id">' + "\n"
					+ '        <ptl:block:field:label:user_type_id/>' + "\n"
					+ '        ' + "\n"
					+ '        <div class="card">' + "\n"
					+ '            <div class="card-body">' + "\n"
					+ '                <ptl:block:field:input:user_type_id/>' + "\n"
					+ '            </div>' + "\n"
					+ '        </div>' + "\n"
					+ '    </div>' + "\n"
					+ '</div>' + "\n"
					+ '' + "\n"
					+ '<div class="h6 font-weight-bold mt-4 mb-2"><ptl:echo translateProjectText(\\$EVC, "Credentials")/></div>' + "\n"
					+ '' + "\n"
					+ '<div class="card">' + "\n"
					+ '    <div class="card-body">' + "\n"
					+ '        <ptl:block:field:username/>' + "\n"
					+ '        <ptl:block:field:password/>' + "\n"
					+ '        <ptl:block:field:security_question_1/>' + "\n"
					+ '        <ptl:block:field:security_answer_1/>' + "\n"
					+ '        <ptl:block:field:security_question_2/>' + "\n"
					+ '        <ptl:block:field:security_answer_2/>' + "\n"
					+ '        <ptl:block:field:security_question_3/>' + "\n"
					+ '        <ptl:block:field:security_answer_3/>' + "\n"
					+ '    </div>' + "\n"
					+ '</div>' + "\n"
					+ '' + "\n"
					+ '<div class="buttons mt-4">' + "\n"
					+ '	<ptl:block:button:insert/>' + "\n"
					+ '	<ptl:block:button:update/>' + "\n"
					+ '	<ptl:block:button:delete/>' + "\n"
					+ '</div>'
			},
			fields: {
				user_type_id: {
					field: {
						"class": "user_type_id form-group row mb-3",
						label: {
							"class": "h6 font-weight-bold mt-4 mb-2"
						},
						input: {
							"class": "form-control form-select col-12 border-0 p-0 h-100 rounded-0",
							extra_attributes: {multiple: ""}
						}
					}
				},
				username: {
					field: {
						"class": "username form-group row mb-3",
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
				active: {
					field: {
						/*"class": "active form-check form-switch form-group row mb-0",
						label: {
							"class": "form-check-label col-12 col-sm-5 col-lg-4 pl-1"
						},
						input: {
							"class": "form-check-input col-12 col-sm-7 col-lg-8  ml-0"
						}*/
						"class": "active form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-5 col-lg-4"
						},
						input: {
							"class": "form-control form-select col-12 col-sm-7 col-lg-8"
						}
					}
				},
				name: {
					field: {
						"class": "name form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-5 col-lg-4"
						},
						input: {
							"class": "form-control col-12 col-sm-7 col-lg-8"
						}
					}
				},
				email: {
					field: {
						"class": "email form-group row mb-0",
						label: {
							"class": "col-form-label col-12 col-sm-5 col-lg-4"
						},
						input: {
							"class": "form-control col-12 col-sm-7 col-lg-8"
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
						"class": "button_save submit_button text-center",
						input: {
							"class": "btn btn-success"
						}
					}
				},
				update: {
					field: {
						"class": "button_save submit_button d-inline float-right float-end",
						input: {
							"class": "btn btn-primary"
						}
					}
				},
				"delete": {
					field: {
						"class": "button_delete submit_button d-inline float-left float-start",
						input: {
							"class": "btn btn-danger"
						}
					}
				}
			}
		};
	}
	
	var password_generator_elm = $('<div class="password_generator" title="Check this box to include a pasword generator."><label>Password Generator:</label><input type="checkbox" class="task_property_field" name="fields[password][field][input][password_generator]" value="1"></div>');
	edit_settings.find(".prop_password .form_containers .fields .field .input_settings .other_settings .allow_null").before(password_generator_elm);
	
	loadEditSettingsBlockSettings(settings_elm, settings_values, empty_settings_values ? {"remove": 0, "sort": 0} : null);
	loadUserEnvironmentsBlockSettings(settings_elm.children(".edit_settings"), settings_values, "user_environments");
	
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
		
		//set active with options type: array
		edit_settings.find(".prop_active > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_options > .options_type").val("array").trigger("change");
		
		//activate password generator by default
		password_generator_elm.children("input").attr("checked", "checked").prop("checked", true);
		
		//set default label for user type id
		edit_settings.find(".prop_user_type_id > .selected_task_properties> .form_containers > .fields > .field > .label_settings > .label_value > input").val("Functions");
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
