<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings article_settings">
	<div class="article_id">
		<label>Article Id:</label>
		<input type="text" class="module_settings_property" name="article_id" value="$_GET['article_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
<?php 
	$fields = array(
		"title" => array("type" => "h1", "label" => "", "class" => "article_title"), 
		"sub_title" => array("type" => "h2", "label" => "", "class" => "article_sub_title"),
		"created_date" => array("type" => "label", "label" => "", "class" => "article_created_date", "show" => 0),
		"modified_date" => array("type" => "label", "label" => "", "class" => "article_modified_date"),
		"tags" => array("type" => "label", "label" => "Tags", "class" => "article_tags"),
		"photo" => array("type" => "image", "label" => "", "class" => "article_photo", 
			"src" => "#photo_url#", 
			"extra_attributes" => array(array("name" => "onError", "value" => '\$(this).parent().remove()')), 
			"next_html" => '"<script>\$(\"img[src=\'\']\").parent().remove()</script>"'
		),
		"summary" => array("type" => "label", "label" => "", "class" => "article_summary", "show" => 0),
		"content" => array("type" => "label", "label" => "", "class" => "article_content"),
		"attachments" => array("label" => "", "class" => "article_attachments"),
		"comments" => array("label" => "", "class" => "article_comments", "show" => 0),
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("article", $fields, array("type" => "label"));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
		"buttons" => false,
	 	"css" => true,
	 	"js" => true,
	));
	echo '<div class="allow_not_published">
		<label>Allow Not Published :</label>
		<input type="checkbox" class="module_settings_property" name="allow_not_published" value="1" />
	</div>';
?>
</div>
