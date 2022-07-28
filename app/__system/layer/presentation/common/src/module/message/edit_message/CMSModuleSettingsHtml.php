<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<div class="edit_settings">
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"message_id" => array("validation_type" => "bigint"),
			"from_user_id" => array("validation_type" => "bigint"),
			"to_user_id" => array("validation_type" => "bigint"),
			"subject",
			"content" => array("type" => "textarea"),
		),
		"buttons" => array(
	 		"view" => true,
	 		"insert" => true,
	 		"update" => false,
	 		"delete" => true,
	 		"undefined" => true,
	 	),
	 	"css" => true,
	 	"js" => true,
	));
?>
</div>
