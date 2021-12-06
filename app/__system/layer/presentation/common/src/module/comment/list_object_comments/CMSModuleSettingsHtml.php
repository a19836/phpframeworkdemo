<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<div class="list_settings">
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array("comment_id", "object_type_id", "object_id", "group", "order"),
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
