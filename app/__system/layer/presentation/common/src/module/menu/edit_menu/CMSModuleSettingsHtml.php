<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings">
	<div class="group_id_type">
		<label>Group Id Type:</label>
		<select class="module_settings_property" name="group_id_type" onChange="onChangeGroupIdType(this)">
			<option value="manual_group_id">Manual group id</option>
			<option value="first_group_id_by_tag_and">First group id with all Tags bellow</option>
			<option value="first_group_id_by_tag_or">First group id with at least one Tag bellow</option>
			<option value="first_group_id_from_related_object">First group id from related object</option>
			<option value="first_group_id_from_related_object_group">First group id from related object group</option>
		</select>
	</div>
	
	<div class="group_id">
		<label>Group Id:</label>
		<input type="text" class="module_settings_property" name="group_id" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="group_id_by_tags">
		<label>Group Tags:</label>
		<input type="text" class="module_settings_property" name="tags" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="group_id_from_related_object">
		<div class="object_type_id">
			<label>Object Type:</label>
			<select class="module_settings_property" name="group_id_by_object[object_type_id]"></select>
		</div>
		<div class="object_id">
			<label>Object Id:</label>
			<input type="text" class="module_settings_property" name="group_id_by_object[object_id]" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="object_group">
			<label>Group:</label>
			<input type="text" class="module_settings_property" name="group_id_by_object[group]" value="" />
		</div>
		<div class="clear"></div>
	</div>
	
	<div style="clear:left; float:none;"></div>
	<div class="menu_class">
		<label>Menu Class:</label>
		<input type="text" class="module_settings_property" name="menu_class" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="list_class">
		<label>List Class:</label>
		<input type="text" class="module_settings_property" name="list_class" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="list_item_class">
		<label>List Item Class:</label>
		<input type="text" class="module_settings_property" name="list_item_class" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"group_name" => array("label" => "Menu Name"),
			"group_tags" => array("label" => "Menu Tags", "title" => 'Tags separated by comma.', "allow_null" => 1, "show" => 0),
			"item_label" => array("label" => "Label"),
			"item_title" => array("label" => "Title", "allow_null" => 1),
			"item_class" => array("label" => "Class", "allow_null" => 1),
			"item_url" => array("label" => "Url", "allow_null" => 1),
			"item_previous_html" => array("label" => "Previous Html", "allow_null" => 1),
			"item_next_html" => array("label" => "Next Html", "allow_null" => 1),
		),
		"buttons" => array(
	 		"view" => true,
	 		"insert" => true,
	 		"update" => true,
	 		"delete" => true,
	 		"undefined" => true,
	 	),
	 	"css" => true,
	 	"js" => true,
	));
	echo CommonModuleSettingsUI::getObjectToObjectFieldsHtml("Relate this menu with the following objects:");
?>
</div>
