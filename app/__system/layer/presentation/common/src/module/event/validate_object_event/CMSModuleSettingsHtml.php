<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings validate_object_to_object_settings">
	<?php echo CommonModuleSettingsUI::getObjectToObjectValidationFieldsHtml($project_common_url_prefix, "Event Id", "event_id"); ?>
</div>
