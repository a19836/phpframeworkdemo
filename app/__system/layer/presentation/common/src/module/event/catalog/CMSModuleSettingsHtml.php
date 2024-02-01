<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script>
	onUpdatePTLFromFieldsSettings = onEventCatalogUpdatePTLFromFieldsSettings;
</script>

<div class="edit_settings catalog_settings">
	<div class="catalog_type">
		<label>Catalog Type:</label>
		<select class="module_settings_property" name="catalog_type" onChange="updateEventsCatalogType(this)">
			<option value="normal_list">Normal List</option>
			<option value="blog_list">Blog List</option>
			<option value="user_list">User List</option>
		</select>
	</div>
	
	<div class="catalog_title">
		<label>Catalog Title:</label>
		<input type="text" class="module_settings_property" name="catalog_title" value="" value_type="string" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="events_type">
		<label>Events Type:</label>
		<select class="module_settings_property" name="events_type" onChange="updateEventsSelectionType(this)">
			<option value="all">Catalog with All Events</option>
			<option value="tags_and">Catalog of Events with all Tags bellow</option>
			<option value="tags_or">Catalog of Events with at least one Tag bellow</option>
			<option value="parent">Catalog of Events by parent</option>
			<option value="parent_group">Catalog of Events by parent group</option>
			<option value="parent_tags_and">Catalog of Events by parent and with all Tags bellow</option>
			<option value="parent_tags_or">Catalog of Events by parent and with at least one Tag bellow</option>
			<option value="parent_group_tags_and">Catalog of Events by parent group and with all Tags bellow</option>
			<option value="parent_group_tags_or">Catalog of Events by parent group and with at least one Tag bellow</option>
			<option value="selected">Catalog of Selected Events</option>
		</select>
	</div>
	
	<div class="catalog_by_parent">
		<div class="catalog_parent_object_type_id">
			<label>Parent Type:</label>
			<select class="module_settings_property" name="object_type_id">
			</select>
		</div>
		<div class="catalog_parent_object_id">
			<label>Parent Id:</label>
			<input type="text" class="module_settings_property" name="object_id" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="catalog_parent_group">
			<label>Group Id:</label>
			<input type="text" class="module_settings_property" name="group" value="" />
		</div>
	</div>
	
	<div class="catalog_by_tags">
		<div class="catalog_tags">
			<label>Tags:</label>
			<input type="text" class="module_settings_property" name="tags" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
	</div>
	
	<div class="catalog_by_selected_events">
		<div class="available_events">
			<label>Add Event:</label>
			<select></select>
			<span class="icon add" onClick="addSelectedEvent(this)">Add</span>
		</div>
		<div class="selected_events">
			<table>
				<tr>
					<th class="table_header event_id">Event ID</th>
					<th class="table_header event_title">Event Title</th>
					<th class="table_header buttons"></th>
				</tr>
				<tr class="no_events">
					<td colspan="3">No events selected...</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="catalog_blog_list">
		<label class="main_catalog_blog_list_label">Catalog Blog List Settings:</label>
		<div class="blog_introduction_events_num">
			<label>Introduction Events Number:</label>
			<input class="module_settings_property" type="text" name="blog_introduction_events_num" value="" value_type="string" />
		</div>
		<div class="blog_featured_events_num">
			<label>Featured Events Number:</label>
			<input class="module_settings_property" type="text" name="blog_featured_events_num" value="" value_type="string" />
		</div>
		<div class="blog_featured_events_cols">
			<label>Featured Events Cols:</label>
			<select class="module_settings_property" name="blog_featured_events_cols">
				<option>3</option>
				<option>2</option>
				<option>1</option>
			</select>
		</div>
		<div class="blog_listed_events_num">
			<label>Listed Events Number:</label>
			<input class="module_settings_property" type="text" name="blog_listed_events_num" value="" value_type="string" />
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="catalog_sort_column">
		<label>Sorting By:</label>
		<select class="module_settings_property" name="catalog_sort_column">
			<option value="">-- NONE --</option>
			<option value="event_id">Event Id</option>
			<option value="title">Event Title</option>
			<option value="sub_title">Event Sub-Title</option>
			<option value="description">Event Description</option>
			<option value="published">Event Published</option>
			<option value="photo_id">Event Photo Id</option>
			<option value="address">Event Address</option>
			<option value="zip_id">Event Zip</option>
			<option value="latitude">Event Latitude</option>
			<option value="longitude">Event Longitude</option>
			<option value="begin_date">Event Begin Date</option>
			<option value="end_date">Event End Date</option>
			<option value="most_recent">Most Recent Events</option>
			<option value="created_date">Event Created Date</option>
			<option value="modified_date">Event Modified Date</option>
			<option class="parent_order" value="order">Event Parent Object Order</option>
			<option class="tag_order" value="tag_order">Event Tag Object Order</option>
		</select>
	</div>
	
	<div class="catalog_sort_order">
		<label>Sorting Order:</label>
		<select class="module_settings_property" name="catalog_sort_order">
			<option value="">-- NONE --</option>
			<option value="asc">Ascendent</option>
			<option value="desc">Descendent</option>
		</select>
	</div>
	
	<div class="clear"></div>
	
	<div class="filter_by_published">
		<label>Publishing Filter:</label>
		<select class="module_settings_property" name="filter_by_published">
			<option value="">Show published and unpublished events</option>
			<option value="1">Only show published events</option>
		</select>
	</div>
	
	<div class="event_properties_url">
		<label>Global Event Props Url:</label>
		<input type="text" class="module_settings_property" name="event_properties_url" value="" url_suffix="?event_id=" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span>&lt;event id&gt;</span>
		<span class="info">The system will append the correspondent event id to this url.<br/>
		If the "Global Event Props Url" is set, when the user clicks in the event, it redirects to that url.</span>
	</div>  
	
<?php 
	echo CommonModuleSettingsUI::getListPaginationSettingsHtml(array("label" => "Catalog Pagination Settings"));

	$fields = array(
		"date_interval" => array("type" => "label", "label" => "", "class" => "catalog_event_date_interval", "show" => 0),
		"date" => array("type" => "label", "label" => "", "class" => "catalog_event_date", "show" => 0),
		"time" => array("type" => "label", "label" => "", "class" => "catalog_event_time", "show" => 0),
		"begin_date" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_date", "show" => 0),
		"end_date" => array("type" => "label", "label" => "", "class" => "catalog_event_end_date", "show" => 0),
		"begin_date_time" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_date_time", "show" => 0),
		"begin_time" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_time"),
		"begin_year" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_year", "show" => 0),
		"begin_month" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_month", "show" => 0),
		"begin_month_short_text" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_month_short_text"),
		"begin_month_long_text" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_month_long_text", "show" => 0),
		"begin_day" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_day"),
		"begin_hour" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_hour", "show" => 0),
		"begin_minute" => array("type" => "label", "label" => "", "class" => "catalog_event_begin_minute", "show" => 0),
		"end_date_time" => array("type" => "label", "label" => "", "class" => "catalog_event_end_date_time", "show" => 0),
		"end_time" => array("type" => "label", "label" => "", "class" => "catalog_event_end_time"),
		"end_year" => array("type" => "label", "label" => "", "class" => "catalog_event_end_year", "show" => 0),
		"end_month" => array("type" => "label", "label" => "", "class" => "catalog_event_end_month", "show" => 0),
		"end_month_short_text" => array("type" => "label", "label" => "", "class" => "catalog_event_end_month_short_text", "show" => 0),
		"end_month_long_text" => array("type" => "label", "label" => "", "class" => "catalog_event_end_month_long_text", "show" => 0),
		"end_day" => array("type" => "label", "label" => "", "class" => "catalog_event_end_day", "show" => 0),
		"end_hour" => array("type" => "label", "label" => "", "class" => "catalog_event_end_hour", "show" => 0),
		"end_minute" => array("type" => "label", "label" => "", "class" => "catalog_event_end_minute", "show" => 0),
		"photo" => array("type" => "image", "label" => "", "class" => "catalog_event_photo", 
			"src" => "#photo_url#", 
			"extra_attributes" => array(array("name" => "onError", "value" => '"\$(this).parent().addClass(\'no_photo\');\$(this).remove();"'))
		),
		"title" => array("type" => "label", "label" => "", "class" => "catalog_event_title"), 
		"sub_title" => array("type" => "label", "label" => "", "class" => "catalog_event_sub_title"),
		"address" => array("type" => "label", "label" => "", "class" => "catalog_event_address", "show" => 0),
		"zip_id" => array("type" => "label", "label" => "", "class" => "catalog_event_zip_id", "show" => 0),
		"country_id" => array("type" => "label", "label" => "", "class" => "catalog_event_country_id", "show" => 0),
		"country" => array("type" => "label", "label" => "", "class" => "catalog_event_country", "show" => 0),
		"map_url" => array("type" => "label", "label" => "", "class" => "catalog_event_map_url", "show" => 0),
		"embed_map_url" => array("type" => "label", "label" => "", "class" => "catalog_event_embed_map_url", "show" => 0),
		"map" => array("type" => "label", "label" => "", "class" => "catalog_event_map", "show" => 0),
		"full_address" => array("type" => "label", "label" => "", "class" => "catalog_event_full_address", "show" => 0),
		"location" => array("type" => "label", "label" => "", "class" => "catalog_event_location", "extra_attributes" => array(array("name" => "onClick", "value" => 'return false'))),
		"description" => array("type" => "label", "label" => "", "class" => "catalog_event_description", "show" => 0),
		"user" => array("type" => "label", "label" => "", "class" => "catalog_event_user", "value" => "#[user][name]#"),
		"created_date" => array("type" => "label", "label" => "", "class" => "catalog_event_created_date", "show" => 0),
		"modified_date" => array("type" => "label", "label" => "", "class" => "catalog_event_modified_date", "show" => 0),
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("event", $fields, array("type" => "label"));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
	 	"search_values" => true,
	 	"buttons" => false,
	 	"css" => '
.module_events_catalog .event {
	display:inline-block;
}
.module_events_catalog .event .catalog_event_description * {
    margin:0;
    padding:0;
}
.module_events_catalog .event .catalog_event_location {
	cursor:auto;
	opacity:.5;
}
.module_events_catalog .event .catalog_event_location .map {
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
.module_events_catalog .event .catalog_event_location .map:before {
    content: \'\\\\e062\';
}',
	 	"js" => '
\$(function () {
	\$(".module_events_catalog .catalog_event_photo img[src=\'\']").each(function (idx, elm) {
	    var elm = \$(elm);
	    elm.parent().addClass(\'no_photo\');
	    elm.remove();
	});
});',
	));
?>
</div>
