<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script>
	onUpdatePTLFromFieldsSettings = onListUpdatePTLFromFieldsSettings;
</script>

<div class="list_settings">
	<div class="query_type">
		<label>Query Type:</label>
		<select class="module_settings_property" name="query_type" onChange="onChangeQueryType(this)">
			<option value="all_users">All users</option>
			<option value="user_by_user_type">Users by user type</option>
			<option value="parent">Users by parent</option>
			<option value="parent_group">Users by parent group</option>
			<option value="parent_and_user_type">Users by parent and user type</option>
			<option value="parent_group_and_user_type">Users by parent group and user type</option>
		</select>
	</div>
	
	<div class="users_by_parent">
		<div class="users_parent_object_type_id">
			<label>Parent Type:</label>
			<select class="module_settings_property" name="object_type_id">
			</select>
		</div>
		<div class="users_parent_object_id">
			<label>Parent Id:</label>
			<input type="text" class="module_settings_property" name="object_id" value="" />
		</div>
		<div class="users_parent_group">
			<label>Group Id:</label>
			<input type="text" class="module_settings_property" name="group" value="" />
		</div>
	</div>
	
	<div class="user_type_id">
		<label>User Type:</label>
		<select class="module_settings_property" name="user_type_id"></select>
	</div>
	
	<div class="do_not_encrypt_password">
		<label>Do not encrypt the user password:</label>
		<input type="checkbox" class="module_settings_property" name="do_not_encrypt_password" value="1" />
		<div class="info">(This means that the passwords will not be encrypted in the DB and the Sysadmin can see it by accessing directly the mu_user table in the DB)</div>
	</div>
<?php 
	$fields = array(
		"selected_item" => array("type" => "checkbox", "value" => "", "allow_null" => 1),
		"user_id" => array("type" => "label"), 
		"username" => array("type" => "text", "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)),
		"password" => array("type" => "password", "value" => "", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"name" => array("type" => "text", "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"email" => array("type" => "email", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"security_question_1" => array("type" => "text", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"security_answer_1" => array("type" => "text", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"security_question_2" => array("type" => "text", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"security_answer_2" => array("type" => "text", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"security_question_3" => array("type" => "text", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"security_answer_3" => array("type" => "text", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"created_date" => array("type" => "datetime", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"modified_date" => array("type" => "datetime", "allow_null" => 1, "extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)), 
		"active" => array("show" => 0, "type" => "select", "options" => array(
			array("value" => 0, "label" => "new"),
			array("value" => 1, "label"=> "active"),
			array("value" => 2, "label"=> "inative"),
		)),
		"user_type_ids" => array("type" => "select", "label" => "User Types", "allow_null" => 1, "extra_attributes" => array(
			//array("name" => "multiple", "value" => 'multiple'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)),
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("user", $fields, array(
		"type" => "text", 
		"allow_null" => 1, 
		"extra_attributes" => array(
			array("name" => "onKeyPress", "value" => '"onListItemFieldKeyPress(this)"'),
			array("name" => "onChange", "value" => '"onListItemFieldKeyPress(this)"'),
		)
	), array("is_edit" => true, "is_list" => true));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
	 	"is_list" => true,
	 	"css" => true,
	 	"js" => '
function onListItemFieldKeyPress(elm) {
	$(elm).parent().closest("tr").find(" > .selected_item > input").prop("checked", true);
}

function onListAddUser(elm) {
	if (!new_user_html) {
		alert("Insert action not allowed!");
		return false;
	}
	
	var table = $(elm).parent().closest("table");
	var tbody = table.children("tbody")[0] ? table.children("tbody") : table;
	var new_index = 0;
	
	var inputs = tbody.find("input, textarea, select");
	$.each(inputs, function(idx, input) {
		if (("" + input.name).substr(0, 6) == "users[") {
			var input_index = parseInt(input.name.substr(6, input.name.indexOf("]") - 6));
			
			if (input_index > new_index)
				new_index = input_index;
		}
	});
	
	new_index++;
	
	var new_item = $(new_user_html.replace(/#idx#/g, new_index)); //new_user_html is a variable that will be created automatically with the correspondent html.
	
	tbody.append(new_item);
	
	return new_item;
}

function onListRemoveNewUser(elm) {
	if (confirm("Do you wish to remove this user?"))
		$(elm).parent().closest("tr").remove();
}

function onSaveMultipleUsers(btn) {
	var tbody = $(btn).parent().closest(".list_items").find(" > .list_container > table > tbody");
	prepareSelectedUsersForAction(tbody);
	
	return true;
}

function onDeleteMultipleUsers(btn, msg) {
	if (!msg || confirm(msg)) {
		var tbody = $(btn).parent().closest(".list_items").find(" > .list_container > table > tbody");
		prepareSelectedUsersForAction(tbody);
		return true;
	}
	
	return false;
}

function prepareSelectedUsersForAction(tbody) {
	if (tbody[0]) {
		var trs = tbody.children("tr");
		
		$.each(trs, function(idx, tr) {
			tr = $(tr);
			var is_selected = tr.find("td.selected_item input[type=checkbox]").is(":checked");
			var inputs = tr.find("td:not(.selected_item)").find("input, select, textarea");
			
			$.each(inputs, function(idy, input) {
				input = $(input);
				
				if (is_selected) {
					if (input[0].hasAttribute("orig-data-allow-null"))
						input.attr("data-allow-null", input.attr("orig-data-allow-null"));
					
					if (input[0].hasAttribute("orig-data-validation-type"))
						input.attr("data-validation-type", input.attr("orig-data-validation-type"));
				}
				else {
					if (input[0].hasAttribute("data-allow-null")) {
						input.attr("orig-data-allow-null", input.attr("data-allow-null"));
						input.removeAttr("data-allow-null");
					}
					
					if (input[0].hasAttribute("data-validation-type")) {
						input.attr("orig-data-validation-type", input.attr("data-validation-type"));
						input.removeAttr("data-validation-type");
					}
				}
			});
		});
	}
}
	 	',
	));
	
	$save_button_settings = array("extra_attributes" => array(array(
		"name" => "onClick",
		"value" => "return onSaveMultipleUsers(this);"
	)));
	$delete_button_settings = array("extra_attributes" => array(array(
		"name" => "onClick",
		"value" => "return onDeleteMultipleUsers(this, 'Do you wish to delete these users?');"
	)));
	echo CommonModuleSettingsUI::getEditButtonFieldsHtml(false, $save_button_settings, $save_button_settings, $delete_button_settings, false);
?>
</div>
