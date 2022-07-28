<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings manage_object_comment_settings">
	<div class="object_type_id">
		<label>Object Type:</label>
		<select class="module_settings_property" name="object_type_id">
		</select>
	</div>
	<div class="object_id">
		<label>Object Id:</label>
		<input type="text" class="module_settings_property" name="object_id" value="$_GET['object_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="session_id">
		<label>Session Id:</label>
		<input type="text" class="module_settings_property" name="session_id" value="$_COOKIE['session_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="user_id">
		<label>or User Id:</label>
		<input type="text" class="module_settings_property" name="user_id" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="clear"></div>
	
	<div class="validate_user">
		<label>Validate User:</label>
		<input type="checkbox" class="module_settings_property" name="validate_user" value="1" checked />
	</div>
	<div class="ok_response">
		<label>Ok Responde:</label>
		<input type="text" class="module_settings_property" name="ok_response" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="error_response">
		<label>Error Responde:</label>
		<input type="text" class="module_settings_property" name="error_response" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="clear"></div>
	
	<?php 
	echo CommonModuleSettingsUI::getEditButtonFieldsHtml(); 
	echo CommonModuleSettingsUI::getObjectToObjectFieldsHtml("Relate this comment with the following objects:");
	?>
</div>
