function loadListUsersSettingsBlockSettings(settings_elm, settings_values) {
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
	
	MyFancyPopup.hidePopup();
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
