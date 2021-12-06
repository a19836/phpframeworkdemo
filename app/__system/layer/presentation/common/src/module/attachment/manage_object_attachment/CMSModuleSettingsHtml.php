<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings manage_object_attachment_settings">
	
	<div class="action">
		<label>Action:</label>
		<select class="module_settings_property" name="action" onChange="onChangeManageObjectAttachmentAction(this)">
			<option value="upload">Upload</option>
			<option value="image_upload_resize">Image Upload and Resize</option>
		</select>
	</div>
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
	<div class="group">
		<label>Group:</label>
		<input type="text" class="module_settings_property" name="group" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="file_variable">
		<label>File Variable:</label>
		<input type="text" class="module_settings_property" name="file_variable" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="resize_width">
		<label>Resize Width:</label>
		<input type="text" class="module_settings_property" name="resize_width" value="" />
	</div>
	<div class="resize_height">
		<label>Resize Height:</label>
		<input type="text" class="module_settings_property" name="resize_height" value="" />
	</div>
	<div class="clear"></div>
	
	<div class="ok_response">
		<label>Ok Responde:</label>
		<input type="text" class="module_settings_property" name="ok_response" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<div class="info">example: foo.com?path=#path#&attachment_id#attachment_id#</div>
	</div>
	<div class="error_response">
		<label>Error Responde:</label>
		<input type="text" class="module_settings_property" name="error_response" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<div class="info">example: Error on attachment #attachment_id# with path: #path#</div>
	</div>
	<div class="clear"></div>
</div>
