<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="list_settings">
	<div class="articles_type">
		<label>Articles Type:</label>
		<select class="module_settings_property" name="articles_type" onChange="updateArticlesSelectionType(this)">
			<option value="all">Catalog with All Articles</option>
			<option value="tags_and">Catalog of Articles with all Tags bellow</option>
			<option value="tags_or">Catalog of Articles with at least one Tag bellow</option>
			<option value="parent">Catalog of Articles by parent</option>
			<option value="parent_group">Catalog of Articles by parent group</option>
			<option value="parent_tags_and">Catalog of Articles by parent and with all Tags bellow</option>
			<option value="parent_tags_or">Catalog of Articles by parent and with at least one Tag bellow</option>
			<option value="parent_group_tags_and">Catalog of Articles by parent group and with all Tags bellow</option>
			<option value="parent_group_tags_or">Catalog of Articles by parent group and with at least one Tag bellow</option>
			<option value="selected">Catalog of Selected Articles</option>
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
	
	<div class="catalog_by_selected_articles">
		<div class="available_articles">
			<label>Add Article:</label>
			<select></select>
			<span class="icon add" onClick="addSelectedArticle(this)">Add</span>
		</div>
		<div class="selected_articles">
			<table>
				<tr>
					<th class="table_header article_id">Article ID</th>
					<th class="table_header article_title">Article Title</th>
					<th class="table_header buttons"></th>
				</tr>
				<tr class="no_articles">
					<td colspan="3">No articles selected...</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="catalog_sort_column">
		<label>Sorting By:</label>
		<select class="module_settings_property" name="catalog_sort_column">
			<option value="">-- NONE --</option>
			<option value="article_id">Article Id</option>
			<option value="title">Article Title</option>
			<option value="sub_title">Article Sub-Title</option>
			<option value="summary">Article Summary</option>
			<option value="content">Article Content</option>
			<option value="published">Article Published</option>
			<option value="photo_id">Article Photo Id</option>
			<option value="created_date">Article Created Date</option>
			<option value="modified_date">Article Modified Date</option>
			<option class="parent_order" value="order">Article Parent Object Order</option>
			<option class="tag_order" value="tag_order">Article Tag Object Order</option>
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
		"article_id", 
		"title", 
		"sub_title",
		"published" => array("available_values" => array(
			array("old_value" => "0", "new_value" => "No"),
			array("old_value" => "1", "new_value" => "Yes"),
		)), 
		"photo" => array("type" => "image", "src" => '#[\\$idx][photo_url]#', "label" => "Photo", "extra_attributes" => array(
			array("name" => "onError", "value" => '"$(this).remove();"'),
		)),
		"summary", 
		"content", 
		"created_date", 
		"modified_date"
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("article", $fields, null, array("is_list" => true));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
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
