<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<div class="edit_settings">
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"group_id" => array("validation_type" => "bigint"),
			"parent_id" => array("validation_type" => "bigint", "allow_null" => 1),
			"item_id" => array("validation_type" => "bigint"),
			"label",
			"title" => array("allow_null" => 1),
			"class" => array("allow_null" => 1),
			"url" => array("allow_null" => 1),
			"order" => array("validation_type" => "smallint", "allow_null" => 1),
			"previous_html" => array("type" => "textarea", "allow_null" => 1),
			"next_html" => array("type" => "textarea", "allow_null" => 1),
		),
		"buttons" => array(
	 		"view" => true,
	 		"insert" => true,
	 		"update" => true,
	 		"delete" => true,
	 		"undefined" => true
	 	),
	 	"css" => true,
	 	"js" => true,
	));
?>
</div>
