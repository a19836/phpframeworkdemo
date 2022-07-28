<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="list_settings">
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array("thread_id", "user_id", "username", "activity_id", "object_type_id", "object_id", "time", "extra", "created_date", "modified_date"),
	 	"is_list" => true,
		"buttons" => array(
	 		"edit" => false,
	 		"delete" => true,
	 	),
	 	"css" => true,
	 	"js" => true,
	));
?>
</div>
