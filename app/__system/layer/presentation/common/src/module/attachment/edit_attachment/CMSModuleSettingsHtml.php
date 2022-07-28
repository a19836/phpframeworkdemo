<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />

<div class="edit_settings">
	<div class="security">
		<label>Security Active:</label>
		<input type="checkbox" class="module_settings_property" name="security" value="1" checked />
		<div class="info">If security is active, the system will check if exists any extension in the path attribute bc of security issues. This is, if there is someone that adds a php extension and someone can find the direct link for this attachment, the code will be executed. But if there is no extension, the http server (apache) won't execute it and will not treat it as a php file. So the EXTENSION MUST NOT EVER BE PRESENT BC OF SECURITY ISSUES! It is in your best interests to leave this field checked!</div>
	</div>
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"attachment_id" => array("validation_type" => "bigint"),
			"name", 
			"type", 
			"size" => array("validation_type" => "bigint", "default_value" => "0"),
			"path"
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
?>
</div>
