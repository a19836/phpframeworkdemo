<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<div class="edit_settings">
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"user_id" => array("validation_type" => "bigint"),
			"action_id" => array("validation_type" => "bigint"),
			"object_type_id" => array("validation_type" => "bigint"),
			"object_id" => array("validation_type" => "bigint"), 
			"time" => array("validation_type" => "bigint"),
			"value" => array("allow_null" => "1")
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
