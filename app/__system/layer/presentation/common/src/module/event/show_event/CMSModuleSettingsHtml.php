<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings event_settings">
	<div class="event_id">
		<label>Event Id:</label>
		<input type="text" class="module_settings_property" name="event_id" value="$_GET['event_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
<?php 
	$fields = array(
		"photo" => array("type" => "image", "label" => "", "class" => "event_photo", 
			"src" => "#photo_url#", 
			"extra_attributes" => array(array("name" => "onError", "value" => '"\$(this).parent().addClass(\'no_photo\');\$(this).remove();"')), 
			"next_html" => '"<script>\$(\".event_photo img[src=\'\']\").each(function (idx, elm) {var elm = \$(elm);elm.parent().addClass(\'no_photo\');elm.remove();});</script>"'
		),
		"date_interval" => array("type" => "label", "label" => "", "class" => "event_date_interval"),
		"date" => array("type" => "label", "label" => "", "class" => "event_date", "show" => 0),
		"time" => array("type" => "label", "label" => "", "class" => "event_time", "show" => 0),
		"begin_date" => array("type" => "label", "label" => "", "class" => "event_begin_date", "show" => 0),
		"end_date" => array("type" => "label", "label" => "", "class" => "event_end_date", "show" => 0),
		"begin_date_time" => array("type" => "label", "label" => "", "class" => "event_begin_date_time", "show" => 0),
		"begin_time" => array("type" => "label", "label" => "", "class" => "event_begin_time"),
		"begin_year" => array("type" => "label", "label" => "", "class" => "event_begin_year", "show" => 0),
		"begin_month" => array("type" => "label", "label" => "", "class" => "event_begin_month", "show" => 0),
		"begin_month_short_text" => array("type" => "label", "label" => "", "class" => "event_begin_month_short_text"),
		"begin_month_long_text" => array("type" => "label", "label" => "", "class" => "event_begin_month_long_text", "show" => 0),
		"begin_day" => array("type" => "label", "label" => "", "class" => "event_begin_day"),
		"begin_hour" => array("type" => "label", "label" => "", "class" => "event_begin_hour", "show" => 0),
		"begin_minute" => array("type" => "label", "label" => "", "class" => "event_begin_minute", "show" => 0),
		"end_date_time" => array("type" => "label", "label" => "", "class" => "event_end_date_time", "show" => 0),
		"end_time" => array("type" => "label", "label" => "", "class" => "event_end_time"),
		"end_year" => array("type" => "label", "label" => "", "class" => "event_end_year", "show" => 0),
		"end_month" => array("type" => "label", "label" => "", "class" => "event_end_month", "show" => 0),
		"end_month_short_text" => array("type" => "label", "label" => "", "class" => "event_end_month_short_text", "show" => 0),
		"end_month_long_text" => array("type" => "label", "label" => "", "class" => "event_end_month_long_text", "show" => 0),
		"end_day" => array("type" => "label", "label" => "", "class" => "event_end_day", "show" => 0),
		"end_hour" => array("type" => "label", "label" => "", "class" => "event_end_hour", "show" => 0),
		"end_minute" => array("type" => "label", "label" => "", "class" => "event_end_minute", "show" => 0),
		"address" => array("type" => "label", "label" => "", "class" => "event_address", "show" => 0),
		"zip_id" => array("type" => "label", "label" => "", "class" => "event_zip_id", "show" => 0),
		"country_id" => array("type" => "label", "label" => "", "class" => "event_country_id", "show" => 0),
		"country" => array("type" => "label", "label" => "", "class" => "event_country", "show" => 0),
		"map_url" => array("type" => "label", "label" => "", "class" => "event_map_url", "show" => 0),
		"embed_map_url" => array("type" => "label", "label" => "", "class" => "event_embed_map_url", "show" => 0),
		"map" => array("type" => "label", "label" => "", "class" => "event_map", "show" => 0),
		"full_address" => array("type" => "label", "label" => "", "class" => "event_full_address", "show" => 0),
		"location" => array("type" => "label", "label" => "", "class" => "event_location"),
		"title" => array("type" => "label", "label" => "", "class" => "event_title"), 
		"sub_title" => array("type" => "label", "label" => "", "class" => "event_sub_title"),
		"created_date" => array("type" => "label", "label" => "", "class" => "event_created_date", "show" => 0),
		"modified_date" => array("type" => "label", "label" => "", "class" => "event_modified_date", "show" => 0),
		"tags" => array("type" => "label", "label" => "Tags", "class" => "event_tags", "show" => 0),
		"description" => array("type" => "label", "label" => "", "class" => "event_description"),
		"attachments" => array("label" => "", "class" => "event_attachments"),
		"user" => array("type" => "label", "label" => "", "class" => "event_user", "value" => "#[user][name]#"),
		"comments" => array("label" => "", "class" => "event_comments", "show" => 0),
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("event", $fields, array("type" => "label"));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
		"buttons" => false,
	 	"css" => '
.module_event .event_location .map {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: \'Glyphicons Halflings\';
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    
    font-size: 15px;
    margin-left: 10px;
    vertical-align: text-top;
    cursor:pointer;
}
.module_event .event_location .map:before {
    content: \'\\\\e062\';
}
',
	 	"js" => true,
	));
	
	echo '<div class="allow_not_published">
		<label>Allow Not Published :</label>
		<input type="checkbox" class="module_settings_property" name="allow_not_published" value="1" />
	</div>';
?>
</div>
