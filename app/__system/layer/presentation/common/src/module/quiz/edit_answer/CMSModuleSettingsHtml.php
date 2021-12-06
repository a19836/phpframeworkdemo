<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<div class="edit_settings">
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"answer_id" => array("validation_type" => "bigint"),
			"question_id" => array("validation_type" => "bigint"),
			"title",
			"description",
			"value" => array("validation_type" => "number"),
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
