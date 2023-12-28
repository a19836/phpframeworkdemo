<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script>
	onUpdatePTLFromFieldsSettings = onQuestionCatalogUpdatePTLFromFieldsSettings;
</script>

<div class="edit_settings catalog_settings">
	<div class="catalog_type">
		<label>Catalog Type:</label>
		<select class="module_settings_property" name="catalog_type" onChange="updateQuestionsCatalogType(this)">
			<option value="normal_list">Normal List</option>
			<option value="user_list">User List</option>
		</select>
	</div>
	
	<div class="catalog_title">
		<label>Catalog Title:</label>
		<input type="text" class="module_settings_property" name="catalog_title" value="" value_type="string" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="questions_type">
		<label>Questions Type:</label>
		<select class="module_settings_property" name="questions_type" onChange="onChangeQuestionsType(this)">
			<option value="all">Catalog with All Questions</option>
			<option value="parent">Catalog of Questions by parent</option>
			<option value="parent_group">Catalog of Questions by parent group</option>
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
	
	<div class="clear"></div>
	
	<div class="catalog_sort_column">
		<label>Sorting By:</label>
		<select class="module_settings_property" name="catalog_sort_column">
			<option value="">-- NONE --</option>
			<option value="question_id">Question Id</option>
			<option value="title">Question Title</option>
			<option value="description">Question Description</option>
			<option value="published">Question Published</option>
			<option value="created_date">Question Created Date</option>
			<option value="modified_date">Question Modified Date</option>
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
			<option value="">Show published and unpublished questions</option>
			<option value="1">Only show published questions</option>
		</select>
	</div>
	
	<div class="question_properties_url">
		<label>Question Properties Url:</label>
		<input type="text" class="module_settings_property" name="question_properties_url" value="" url_suffix="?question_id=" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span>&lt;question id&gt;</span>
		<span class="info">The system will append the correspondent question id to this url.</span>
	</div>  
	
<?php 
	echo CommonModuleSettingsUI::getListPaginationSettingsHtml(array("label" => "Catalog Pagination Settings"));

	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"created_date" => array("type" => "label", "label" => "", "class" => "catalog_question_created_date", "show" => 0),
			"modified_date" => array("type" => "label", "label" => "", "class" => "catalog_question_modified_date"),
			"title" => array("type" => "label", "label" => "", "class" => "catalog_question_title"), 
			"description" => array("type" => "label", "label" => "", "class" => "catalog_question_description"),
		),
	 	"search_values" => true,
		"buttons" => false,
	 	"css" => '.module_questions_catalog .question {
	display:inline-block;
}',
	 	"js" => true,
	));
?>
</div>
