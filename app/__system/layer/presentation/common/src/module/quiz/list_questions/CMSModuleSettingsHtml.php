<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/list_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="list_settings">
	<div class="questions_type">
		<label>Questions Type:</label>
		<select class="module_settings_property" name="questions_type" onChange="onChangeQuestionsType(this)">
			<option value="all">List with All Questions</option>
			<option value="parent">List of Questions by parent</option>
			<option value="parent_group">List of Questions by parent group</option>
		</select>
	</div>
	
	<div class="list_by_parent">
		<div class="list_parent_object_type_id">
			<label>Parent Type:</label>
			<select class="module_settings_property" name="object_type_id">
			</select>
		</div>
		<div class="list_parent_object_id">
			<label>Parent Id:</label>
			<input type="text" class="module_settings_property" name="object_id" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="list_parent_group">
			<label>Group Id:</label>
			<input type="text" class="module_settings_property" name="group" value="" />
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="list_sort_column">
		<label>Sorting By:</label>
		<select class="module_settings_property" name="list_sort_column">
			<option value="">-- NONE --</option>
			<option value="question_id">Question Id</option>
			<option value="title">Question Title</option>
			<option value="description">Question Description</option>
			<option value="published">Question Published</option>
			<option value="created_date">Question Created Date</option>
			<option value="modified_date">Question Modified Date</option>
		</select>
	</div>
	
	<div class="list_sort_order">
		<label>Sorting Order:</label>
		<select class="module_settings_property" name="list_sort_order">
			<option value="">-- NONE --</option>
			<option value="asc">Ascendent</option>
			<option value="desc">Descendent</option>
		</select>
	</div>
	
	<div class="clear"></div>
	
<?php 
	echo CommonModuleSettingsUI::getListPaginationSettingsHtml(array("label" => "List Pagination Settings"));

	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"question_id" => array("class" => "question_id hidden"), 
			"title", 
			"description" => array("show" => 0), 
			"published" => array("available_values" => array(
				array("old_value" => "0", "new_value" => "No"),
				array("old_value" => "1", "new_value" => "Yes"),
			)), 
			"created_date", 
			"modified_date"
		),
	 	"is_list" => true,
		"buttons" => array(
	 		"edit" => true,
	 		"delete" => true,
	 	),
	 	"css" => true,
	 	"js" => true,
	 	"table_class" => "list_table table table-striped table-hover table-sm"
	));
?>
</div>
