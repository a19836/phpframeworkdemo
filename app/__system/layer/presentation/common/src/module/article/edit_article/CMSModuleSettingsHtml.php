<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings">
<?php 
	$fields = array(
		"article_id" => array("validation_type" => "bigint"),
		"title", 
		"sub_title", 
		"published" => array("allow_null" => 1),
		"tags",
		"photo_id" => array("label" => "Photo", "allow_null" => 1),
		"summary" => array("type" => "textarea"),
		"content" => array("type" => "textarea"),
		"allow_comments" => array("allow_null" => 1),
		"article_attachments"
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("article", $fields, null, array("is_edit" => true));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
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
	echo CommonModuleSettingsUI::getObjectToObjectFieldsHtml("Relate this article with the following objects:");
?>
	<div class="html_images_settings">
		<label>Content/Summary Html images settings:</label>
		<div class="info">This is used for the cases where the user pastes some html code with images with base64 data, this is, binary images (not the url). In this case the system needs to replace these images with the appropriate url, otherwise the browsers textarea/editores will freeze if the size exceeds 1MB of text.</div>
		
		<div class="upload_url">
			<label>Upload Url:</label>
			<input type="text" class="module_settings_property" name="upload_url" value="" />
			<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
			<div class="info">If the url contains any of the following variables: #html_image_group# and #article_id#, the system will replace them by the correspondent values. (The #html_image_group# variable corresponds to the Article Content/Summary html images group)</div>
		</div>
		<div class="attachment_id_regex">
			<label>Attachment Id Regex:</label>
			<input type="text" class="module_settings_property" name="attachment_id_regex" value="/\/[a-z0-9]+\/[a-z0-9]+\/[a-z0-9]+\/attachment_([0-9]+)_[a-z0-9]+/i" />
			<div class="info">In order to get the attachment_id from the html images' url, the system needs to know the correspondent regex.</div>
		</div>
	</div>
</div>
