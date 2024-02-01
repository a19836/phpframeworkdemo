<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="list_settings">
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
	
	<div class="clear"></div>
	
	<div class="catalog_sort_column">
		<label>Sorting By:</label>
		<select class="module_settings_property" name="catalog_sort_column">
			<option value="">-- NONE --</option>
			<option value="event_id">Event Id</option>
			<option value="title">Event Title</option>
			<option value="sub_title">Event Sub-Title</option>
			<option value="summary">Event Summary</option>
			<option value="content">Event Content</option>
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
	
<?php 
	echo CommonModuleSettingsUI::getListPaginationSettingsHtml(array("label" => "List Pagination Settings"));

	$fields = array(
		"event_id", 
		"title" => array("label" => "Details"), 
		"sub_title",
		"published" => array("available_values" => array(
			array("old_value" => "0", "new_value" => "No"),
			array("old_value" => "1", "new_value" => "Yes"),
		)), 
		"photo" => array("type" => "image", "src" => '#[\\$idx][photo_url]#', "label" => "Photo", "extra_attributes" => array(
			array("name" => "onError", "value" => '"$(this).remove();"'),
		)),
		"description", 
		"address" => array("show" => 0), 
		"zip_id" => array("show" => 0), 
		"locality" => array("show" => 0), 
		"country_id" => array("available_values" => array(
			array("old_value" => "1", "new_value" => "Portugal"),
		), "show" => 0),
		"map_url" => array("show" => 0),
		"embed_map_url" => array("show" => 0),
		"map" => array("show" => 0),
		"full_address" => array("show" => 0),
		"location" => array("show" => 0),
		"latitude" => array("show" => 0), 
		"longitude" => array("show" => 0),
		"date_interval" => array("show" => 0),
		"date" => array("show" => 0),
		"time" => array("show" => 0),
		"begin_date" => array("show" => 0), 
		"end_date" => array("show" => 0), 
		"begin_date_time" => array("show" => 0),
		"begin_time",
		"begin_year" => array("show" => 0),
		"begin_month" => array("show" => 0),
		"begin_month_short_text",
		"begin_month_long_text" => array("show" => 0),
		"begin_day",
		"begin_hour" => array("show" => 0),
		"begin_minute" => array("show" => 0),
		"end_date_time" => array("show" => 0),
		"end_time",
		"end_year" => array("show" => 0),
		"end_month" => array("show" => 0),
		"end_month_short_text" => array("show" => 0),
		"end_month_long_text" => array("show" => 0),
		"end_day" => array("show" => 0),
		"end_hour" => array("show" => 0),
		"end_minute" => array("show" => 0),
		"created_date" => array("show" => 0), 
		"modified_date" => array("show" => 0)
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("event", $fields, null, array("is_list" => true));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
	 	"is_list" => true,
		"buttons" => array(
	 		"edit" => true,
	 		"delete" => true,
	 	),
	 	"css" => ".module_list_events table img {
	width:100px;
}",
	 	"js" => true,
	 	"table_class" => "list_table table table-hover"
	));
?>
</div>
