function loadListUsersSettingsBlockSettings(settings_elm, settings_values) {
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
					+ '            		<tr>				' + "\n"
					+ '            			<th class="list_column name border-0"><ptl:block:field:label:name/></th>' + "\n"
					+ '            			<th class="list_column username border-0"><ptl:block:field:label:username/></th>' + "\n"
					+ '            			<th class="list_column email border-0"><ptl:block:field:label:email/></th>' + "\n"
					+ '            			<th class="list_column active border-0 text-center"><ptl:block:field:label:active/></th>' + "\n"
					+ '            			<th class="list_column edit_action border-0"></th>' + "\n"
					+ '            		</tr>' + "\n"
					+ '            	</thead>' + "\n"
					+ '            	<tbody>' + "\n"
					+ '            		<ptl:if is_array(\\$input)>' + "\n"
					+ '            			<ptl:foreach \\$input i item>' + "\n"
					+ '            				<tr>						' + "\n"
					+ '            					<td class="list_column name"><ptl:block:field:input:name/></td>' + "\n"
					+ '            					<td class="list_column username"><ptl:block:field:input:username/></td>' + "\n"
					+ '            					<td class="list_column email"><ptl:block:field:input:email/></td>' + "\n"
					+ '            					<!--td class="list_column active text-center pt-2">' + "\n"
					+ '					    				<div class="form-check form-switch m-n3">' + "\n"
					+ '					 					<input class="form-check-input" type="checkbox" disabled <ptl:echo \\$item[active] ? checked : \'\'/>>' + "\n"
					+ '				    					</div>' + "\n"
					+ '            					</td-->' + "\n"
					+ '            					<td class="list_column active text-center"><ptl:block:field:input:active/></td>' + "\n"
					+ '            					<td class="list_column edit_action"><ptl:block:button:edit/></td>' + "\n"
					+ '            				</tr>' + "\n"
					+ '            			</ptl:foreach>' + "\n"
					+ '            		</ptl:if>' + "\n"
					+ '            	</tbody>' + "\n"
					+ '            </table>' + "\n"
					+ '        </div>' + "\n"
					+ '        <div class="bottom_pagination">' + "\n"
					+ '        	<ptl:block:bottom-pagination/>' + "\n"
					+ '        </div>' + "\n"
					+ '    </div>' + "\n"
					+ '</div>'
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
