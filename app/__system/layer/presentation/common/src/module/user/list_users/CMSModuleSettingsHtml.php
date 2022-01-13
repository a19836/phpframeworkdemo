<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

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
<?php 
	$fields = array(
		"user_id", 
		"username", 
		"password" => array("show" => 0), 
		"name", 
		"email", 
		"security_question_1" => array("show" => 0), 
		"security_answer_1" => array("show" => 0), 
		"security_question_2" => array("show" => 0), 
		"security_answer_2" => array("show" => 0), 
		"security_question_3" => array("show" => 0), 
		"security_answer_3" => array("show" => 0), 
		"active" => array("show" => 0), 
		"created_date" => array("show" => 0), 
		"modified_date"
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("user", $fields, null, array("is_list" => true));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
	 	"is_list" => true,
		"buttons" => array(
	 		"edit" => true,
	 		"delete" => true,
	 	),
	 	"css" => true,
	 	"js" => true,
	));
?>
</div>
