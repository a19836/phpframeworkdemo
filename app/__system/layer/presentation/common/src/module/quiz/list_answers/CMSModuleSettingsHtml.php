<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<div class="list_settings">
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array("answer_id", "question_id", "title", "description", "value", "created_date", "modified_date"),
	 	"is_list" => true,
		"buttons" => array(
	 		"edit" => true,
	 		"delete" => true,
	 	),
	 	"css" => true,
	 	"js" => true,
	));
?>
</div>
