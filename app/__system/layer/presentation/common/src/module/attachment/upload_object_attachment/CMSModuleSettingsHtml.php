<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings upload_object_attachment_settings">
	<div class="object_type_id">
		<label>Object Type:</label>
		<select class="module_settings_property" name="object_type_id"></select>
	</div>
	<div class="object_id">
		<label>Object Id:</label>
		<input type="text" class="module_settings_property" name="object_id" value="" />
	</div>
	<div class="group">
		<label>Group:</label>
		<input type="text" class="module_settings_property" name="group" value="" />
	</div>
	
	<div class="template">
		<label>Template:</label>
		<select class="module_settings_property" name="template">
			<option value="individual_items_upload">Individual Items Upload</option>
			<option value="drag_and_drop_upload">Drag and Drop Upload</option>
		</select>
	</div>
	<div class="main_label">
		<label>Main Label:</label>
		<input type="text" class="module_settings_property" name="main_label" value="" />
	</div>
	<div class="item_label">
		<label>Item Label:</label>
		<input type="text" class="module_settings_property" name="item_label" value="" />
	</div>
	
<?php 
	echo CommonModuleSettingsUI::getStyleFieldsHtml(); 
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
?>
</div>
