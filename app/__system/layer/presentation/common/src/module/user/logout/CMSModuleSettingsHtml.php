<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $project_common_url_prefix; ?>module/common/validate_module_object/settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/validate_module_object/settings.js"></script>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings logout_settings">
	<?php 
		echo CommonModuleSettingsUI::getStyleFieldsHtml(true, false); 
	?>
	
	<div class="validate_object_to_object_settings">
		<?php 
			echo CommonModuleSettingsUI::getObjectToObjectValidationActionsHtml(array(
				"validation_action_label" => "On Logout OK Action",
				"validation_message_label" => "Logout OK Message",
				"validation_class_label" => "Logout OK Class",
				"validation_redirect_label" => "Redirect Url",
				"validation_blocks_execution_label" => "Blocks Execution",
				"non_validation_action_label" => "On Logout Error Action",
				"non_validation_message_label" => "Logout Error Message",
				"non_validation_class_label" => "Logout Error Class",
				"non_validation_redirect_label" => "Redirect Url",
				"non_validation_blocks_execution_label" => "Blocks Execution",
			));
		?>
	</div>
	
	<div class="auth0_settings">
		<label>If you wish to logout from Auth0 system too, please fill the fields bellow:</label>
		
		<div class="domain">
			<label>Domain:</label>
			<input class="module_settings_property" name="domain" value="" />
		</div>
		<div class="client_id">
			<label>Client ID:</label>
			<input class="module_settings_property" name="client_id" value="" />
		</div>
		<div class="notes">
			<label>Notes:</label>
			<ul>
				<li>Please add all entity/page's urls where this block is related, in the "Allowed Logout URLs" section of the Auth0 admin panel.</li>
				<li>To open your Auth0 admin panel, please click <a href="https://manage.auth0.com/dashboard/" target="auth0_admin_panel">here</a></li>
			</ul>
		</div>
	</div>
	
	<?php 
		echo CommonModuleSettingsUI::getCssFieldsHtml();
		echo CommonModuleSettingsUI::getJsFieldsHtml();
	?>
</div>
