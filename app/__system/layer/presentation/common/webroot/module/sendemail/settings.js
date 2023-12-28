$(function () {
	var settings_prop = $(".settings_prop");
	var fields = settings_prop.children(".selected_task_properties").find(".fields .field");
	
	fields.children(".input_settings").children(".input_name").hide();
	
	initObjectBlockSettings("edit_settings", saveEditSettings, "saveEditSettings");
});

function loadSendEmailBlockSettings(settings_elm, settings_values) {
	var edit_settings = settings_elm.children(".edit_settings");
	var empty_settings_values = !settings_values || ($.isArray(settings_values) && settings_values.length == 0);
	
	if (empty_settings_values) {
		settings_values = {
			ptl: {
				code: '<ptl:block:field:from/>' + "\n"
					+ '<ptl:block:field:name/>' + "\n"
					+ '<ptl:block:field:subject/>' + "\n"
					+ '<ptl:block:field:message/>' + "\n"
					+ '<div class="buttons">' + "\n"
					+ '	<ptl:block:button:insert/>' + "\n"
					+ '</div>'
			},
			fields: {
				from: {
					field: {
						"class": "from form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-2"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-10"
						}
					}
				},
				to: {
					field: {
						"class": "to form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-2"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-10"
						}
					}
				},
				reply_to: {
					field: {
						"class": "reply_to form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-2"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-10"
						}
					}
				},
				name: {
					field: {
						"class": "name form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-2"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-10"
						}
					}
				},
				name: {
					field: {
						"class": "name form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-2"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-10"
						}
					}
				},
				subject: {
					field: {
						"class": "subject form-group row mb-3",
						label: {
							"class": "col-form-label col-12 col-sm-4 col-lg-2"
						},
						input: {
							"class": "form-control col-12 col-sm-8 col-lg-10"
						}
					}
				},
				message: {
					field: {
						"class": "message form-group row mb-0",
						label: {
							"class": "col-form-label col-12"
						},
						input: {
							"class": "form-control col-12"
						}
					}
				}
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
	
	loadEditSettingsBlockSettings(settings_elm, settings_values, empty_settings_values ? {"remove": 0, "sort": 0} : null);
	
	if (empty_settings_values) {
		//set default label for field: 'from'
		edit_settings.find(".prop_from > .selected_task_properties> .form_containers > .fields > .field > .label_settings > .label_value > input").val("Email:");
	}
}
