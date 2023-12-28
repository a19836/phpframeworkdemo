function loadListAndEditUsersWitUserTypesSettingsBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var list_settings = settings_elm.children(".list_settings");
	var empty_settings_values = !settings_values || ($.isArray(settings_values) && settings_values.length == 0);
	
	if (empty_settings_values) {
		settings_values = {
			ptl: {
				code: '<div class="card">' + "\n"
					+ '    <div class="card-body">' + "\n"
					+ '        <div class="list_container table-responsive">' + "\n"
					+ '            <table class="list_table table table-striped table-hover table-sm">' + "\n"
					+ '            	<thead>' + "\n"
					+ '            		<tr>	' + "\n"
					+ '            			<th class="list_column selected_item border-0">' + "\n"
					+ '            			    <input type="checkbox" onChange="toggleUsersSelection(this)" />' + "\n"
					+ '            			</th>' + "\n"
					+ '			            <th class="list_column user_id border-0 text-left hidden"><ptl:block:field:label:user_id/></th>' + "\n"
					+ '            			<th class="list_column name border-0 text-left"><ptl:block:field:label:name/></th>' + "\n"
					+ '            			<th class="list_column username border-0 text-left"><ptl:block:field:label:username/></th>' + "\n"
					+ '            			<th class="list_column password border-0 text-left"><ptl:block:field:label:password/></th>' + "\n"
					+ '            			<th class="list_column email border-0 text-left"><ptl:block:field:label:email/></th>' + "\n"
					+ '            			<th class="list_column active border-0 text-center"><ptl:block:field:label:active/></th>' + "\n"
					+ '            			<th class="list_column user_type_ids border-0 text-left"><ptl:block:field:label:user_type_ids/></th>' + "\n"
            			+ '            			<th class="list_column edit_action border-0">' + "\n"
					+ '            			    <span class="cursor-pointer text-danger" onClick="onListAddUser(this)"><i class="fa fa-plus"></i></span>' + "\n"
					+ '						</th>' + "\n"
					+ '            			</tr>' + "\n"
					+ '            	</thead>' + "\n"
					+ '            	<tbody>' + "\n"
					+ '            		<ptl:if is_array(\\$input)>' + "\n"
					+ '            			<ptl:foreach \\$input i item>' + "\n"
					+ '            				<tr>' + "\n"
					+ '            					<td class="list_column selected_item"><ptl:block:field:input:selected_item/></td>' + "\n"
					+ '            					<td class="list_column user_id hidden"><ptl:block:field:input:user_id/></td>' + "\n"
					+ '            					<td class="list_column name"><ptl:block:field:input:name/></td>' + "\n"
					+ '            					<td class="list_column username"><ptl:block:field:input:username/></td>' + "\n"
					+ '            					<td class="list_column password"><ptl:block:field:input:password/></td>' + "\n"
					+ '            					<td class="list_column email"><ptl:block:field:input:email/></td>' + "\n"
					+ '            					<!--td class="list_column active text-center pt-2">' + "\n"
					+ '					    				<div class="form-check form-switch m-n3">' + "\n"
					+ '					 					<input class="form-check-input" type="checkbox" <ptl:echo \\$item[active] ? checked : \'\'/>>' + "\n"
					+ '				    					</div>' + "\n"
					+ '            					</td-->' + "\n"
					+ '            					<td class="list_column active text-center"><ptl:block:field:input:active/></td>' + "\n"
					+ '            					<td class="list_column user_type_ids"><ptl:block:field:input:user_type_ids/></td>' + "\n"
					+ '            					<td class="list_column edit_action"></td>' + "\n"
					+ '            				</tr>' + "\n"
					+ '            			</ptl:foreach>' + "\n"
					+ '            		</ptl:if>' + "\n"
					+ '            	</tbody>' + "\n"
					+ '            </table>' + "\n"
					+ '        </div>' + "\n"
					+ '    </div>' + "\n"
					+ '</div>' + "\n"
					+ '' + "\n"
					+ '<div class="bottom_pagination">' + "\n"
					+ '	<ptl:block:bottom-pagination/>' + "\n"
					+ '</div>' + "\n"
					+ '' + "\n"
					+ '<div class="buttons">' + "\n"
					+ '	<ptl:block:button:update/>' + "\n"
					+ '	<ptl:block:button:delete/>' + "\n"
					+ '</div>'
			},
			fields: {
				name: {
					field: {
						input: {
							"class": "form-control h-100 bg-transparent border-0 p-0"
						}
					}
				},
				username: {
					field: {
						input: {
							"class": "form-control h-100 bg-transparent border-0 p-0"
						}
					}
				},
				password: {
					field: {
						input: {
							"class": "form-control h-100 bg-transparent border-0 p-0"
						}
					}
				},
				email: {
					field: {
						input: {
							"class": "form-control h-100 bg-transparent border-0 p-0"
						}
					}
				},
				active: {
					field: {
						input: {
							"class": "form-control form-select h-100 bg-transparent border-0 p-0"
						}
					}
				},
				user_type_ids: {
					field: {
						input: {
							"class": "form-control form-select h-100 bg-transparent border-0 p-0"
						}
					}
				}
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
	
	$.ajax({
		url: call_module_file_prefix_url.replace("#module_file_path#", "get_data"),
		success: function(data) {
			if (data) {
				var object_types = data["object_types"];
				var user_types = data["user_types"];
				
				var options = '';
				$.each(object_types, function(index, object_type) {
					options += '<option value="' + object_type["object_type_id"] + '">' + object_type["name"] + '</option>';
				});
				list_settings.find(".users_by_parent .users_parent_object_type_id select").html(options);
				
				options = '';
				$.each(user_types, function(index, user_type) {
					options += '<option value="' + user_type["user_type_id"] + '">' + user_type["name"] + '</option>';
				});
				
				var select = list_settings.children(".user_type_id").children("select");
				select.html(options);
			}
		},
		error: function() {
			StatusMessageHandler.showError("Error trying to load user types.\nPlease try again...");
		},
		dataType: "json",
		async: false,
	});
	
	loadListSettingsBlockSettings(settings_elm, settings_values, empty_settings_values ? {"remove": 0, "sort": 0} : null);
	onChangeQueryType( list_settings.children(".query_type").children("select")[0] );
	
	if (empty_settings_values) {
		//prepare fields with extra_attributes
		for (var field_id in settings_values["fields"]) {
			var input_extra_attributes = list_settings.find(".prop_" + field_id + " > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_extra_attributes");
			
			if (input_extra_attributes.find(" > .attributes .task_property_field").length > 0)
				input_extra_attributes.find(" > .extra_attributes_type").val("array").trigger("change");
		}
		
		//set active with options type: array
		list_settings.find(".prop_active > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_options > .options_type").val("array").trigger("change");
		
		//prepare buttons with extra_attributes
		for (var button_id in settings_values["buttons"]) {
			var input_extra_attributes = list_settings.find(".button_settings_" + button_id + " > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_extra_attributes");
			
			if (input_extra_attributes.find(" > .attributes .task_property_field").length > 0)
				input_extra_attributes.find(" > .extra_attributes_type").val("array").trigger("change");
		}
	}
	
	//prepare fields
	list_settings.find(".settings_prop.prop_user_id > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_type").hide();
	
	var input_settings = list_settings.find(".settings_prop.prop_user_type_ids > .selected_task_properties > .form_containers > .fields > .field > .input_settings");
	input_settings.children(".input_type").hide();
	input_settings.children(".input_options").remove();
	
	list_settings.find(".settings_prop.prop_active > .selected_task_properties > .form_containers > .fields > .field > .input_settings > .input_type select option").each(function (idx, option) {
		option = $(option);
		var option_value = option.val();
		
		if (option_value != "select" && option_value != "checkbox")
			option.remove();
	});
	
	var prop_selected_item = list_settings.find(".settings_prop.prop_selected_item");
	var field = prop_selected_item.find(" > .selected_task_properties > .form_containers > .fields > .field ");
	prop_selected_item.children(".show_settings_prop, .settings_prop_search_value").remove();
	var input_settings = field.children(".input_settings");
	input_settings.children(".input_type").hide();
	input_settings.children(".input_options").remove();
	
	if (empty_settings_values) {
		list_settings.find(".status_action_delete > .on_ok_action > select").val("show_message");
		
		field.find(" > .label_settings > .label_value > .task_property_field").val("");
	}
	
	list_settings.find(".allow_insertion > input").click(function() {
		var elm = $(this);
		var next_input = field.find(" > .label_settings > .label_next_html > .task_property_field");
		var v = "" + next_input.val();
		
		if (elm.is(":checked")) {
			if (v.indexOf('onListAddUser') == -1)
				v += '<span class="glyphicon glyphicon-plus icon add" onClick="onListAddUser(this)">Add</span>';
		}
		else if (v.indexOf('onListAddUser') != -1) {
			v = v.replace('<span class="glyphicon glyphicon-plus icon add" onClick="onListAddUser(this)">Add</span>', '');
			v = v.replace('"<span class=\\"glyphicon glyphicon-plus icon add\\" onClick=\\"onListAddUser(this)\\">Add</span>"', '');
		}
		
		next_input.val(v);
	});
	
	//setTimeout bc of the onElsTabChange inside of the loadListSettingsBlockSettings
	setTimeout(function() {
		list_settings.find(".settings_prop > .selected_task_properties > .form_containers > .fields > .field > .class").show();
	}, 110);
	
	MyFancyPopup.hidePopup();
}

function onListUpdatePTLFromFieldsSettings(elm, settings, code, external_vars) {
	if (settings["buttons"]) { //preparing code for buttons
		//console.log(settings);
		
		code += "\n" + '<div class="buttons">';
		
		for (var k in settings["buttons"]) {
			var kion = k == "insert" ? "insertion" : (k == "delete" ? k.substr(0, k.length - 1) + "ion" : k);
			
			if (settings["allow_" + kion] == "1") { //prepare form field ptl code
				var field = settings["buttons"][k];
				
				if (field.hasOwnProperty("ptl") && field["ptl"].hasOwnProperty("code") && field["code"]) {
					code += "\n\t" + field["ptl"]["code"];

					if (field["ptl"]["external_vars"])
						for (var n in field["ptl"]["external_vars"])
							external_vars[n] = field["ptl"]["external_vars"][n];
				}
				else
					code += "\n\t" + '<ptl:block:button:' + k + '/>';
			}
		}
		
		code += "\n" + '</div>';
	}
	
	return code;
}

function onChangeQueryType(elm) {
	elm = $(elm);
	
	var value = elm.val();
	var list_settings = elm.parent().parent();
	
	list_settings.children(".users_by_parent, .user_type_id").hide();
	list_settings.find(".users_by_parent > .users_parent_group").hide();
	
	if (value == "user_by_user_type" || value == "parent_and_user_type" || value == "parent_group_and_user_type") 
		list_settings.children(".user_type_id").show();
	
	if (value == "parent" || value == "parent_group" || value == "parent_and_user_type" || value == "parent_group_and_user_type")
		list_settings.children(".users_by_parent").show();
	
	if (value == "parent_group" || value == "parent_group_and_user_type")
		list_settings.find(".users_by_parent > .users_parent_group").show();
}
