<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script>
	onUpdatePTLFromFieldsSettings = onArticleCatalogUpdatePTLFromFieldsSettings;
</script>

<div class="edit_settings catalog_settings">
	<div class="catalog_type">
		<label>Catalog Type:</label>
		<select class="module_settings_property" name="catalog_type" onChange="updateArticlesCatalogType(this)">
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
	
	<div class="catalog_blog_list">
		<label class="main_catalog_blog_list_label">Catalog Blog List Settings:</label>
		<div class="blog_introduction_articles_num">
			<label>Introduction Articles Number:</label>
			<input class="module_settings_property" type="text" name="blog_introduction_articles_num" value="" value_type="string" />
		</div>
		<div class="blog_featured_articles_num">
			<label>Featured Articles Number:</label>
			<input class="module_settings_property" type="text" name="blog_featured_articles_num" value="" value_type="string" />
		</div>
		<div class="blog_featured_articles_cols">
			<label>Featured Articles Cols:</label>
			<select class="module_settings_property" name="blog_featured_articles_cols">
				<option>3</option>
				<option>2</option>
				<option>1</option>
			</select>
		</div>
		<div class="blog_listed_articles_num">
			<label>Listed Articles Number:</label>
			<input class="module_settings_property" type="text" name="blog_listed_articles_num" value="" value_type="string" />
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
	
	<div class="clear"></div>
	
	<div class="filter_by_published">
		<label>Publishing Filter:</label>
		<select class="module_settings_property" name="filter_by_published">
			<option value="">Show published and unpublished articles</option>
			<option value="1">Only show published articles</option>
		</select>
	</div>
	
	<div class="article_properties_url">
		<label>Article Properties Url:</label>
		<input type="text" class="module_settings_property" name="article_properties_url" value="" url_suffix="?article_id=" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span>&lt;article id&gt;</span>
		<span class="info">The system will append the correspondent article id to this url.<br/>
		If the "Global Article Props Url" is set, when the user clicks in the article, it redirects to that url.</span>
	</div>  
	
<?php 
	echo CommonModuleSettingsUI::getListPaginationSettingsHtml(array("label" => "Catalog Pagination Settings"));
	
	$fields = array(
		"created_date" => array("type" => "label", "label" => "", "class" => "catalog_article_created_date", "show" => 0),
		"modified_date" => array("type" => "label", "label" => "", "class" => "catalog_article_modified_date", "show" => 0),
		"photo" => array("type" => "image", "label" => "", "class" => "catalog_article_photo", 
			"src" => "#photo_url#", 
			"extra_attributes" => array(array("name" => "onError", "value" => '\$(this).parent().remove()'))
		),
		"title" => array("type" => "h1", "label" => "", "class" => "catalog_article_title"), 
		"sub_title" => array("type" => "h2", "label" => "", "class" => "catalog_article_sub_title"),
		"summary" => array("type" => "label", "label" => "", "class" => "catalog_article_summary"),
		"content" => array("type" => "label", "label" => "", "class" => "catalog_article_content", "show" => 0),
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("article", $fields, array("type" => "label"));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
		"buttons" => false,
	 	"css" => true,
	 	"js" => '
\$(function () {
	\$(".module_articles_catalog .catalog_article_photo img[src=\'\']").parent().remove();
});',
	));
?>
</div>
