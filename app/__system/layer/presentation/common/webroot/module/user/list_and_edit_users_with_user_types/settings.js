function loadListAndEditUsersWitUserTypesSettingsBlockSettings(settings_elm, settings_values) {
	MyFancyPopup.init({
		parentElement: window,
	});
	MyFancyPopup.showOverlay();
	MyFancyPopup.showLoading();
	
	var list_settings = settings_elm.children(".list_settings");
	
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
	
	loadListSettingsBlockSettings(settings_elm, settings_values);
	onChangeQueryType( list_settings.children(".query_type").children("select")[0] );
	
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
	
	if (!settings_values || $.isEmptyObject(settings_values)) {
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
