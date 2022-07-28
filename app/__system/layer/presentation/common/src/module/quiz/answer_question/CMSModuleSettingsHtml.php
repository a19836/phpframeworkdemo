<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings">
	<div class="session_id">
		<label>Session Id:</label>
		<input type="text" class="module_settings_property" name="session_id" value="$_COOKIE['session_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="user_id">
		<label>or User Id:</label>
		<input type="text" class="module_settings_property" name="user_id" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="clear"></div>
	
	<div class="question_type">
		<label>Question Type:</label>
		<select class="module_settings_property" name="question_type" onChange="onChangeQuestionType(this)">
			<option value="">By question id</option>
			<option value="get_next_by_parent">Get next question by object parent</option>
			<option value="get_next_by_parent_group">Get next question by object parent group</option>
		</select>
	</div>
	<div class="question_id">
		<label>Question Id:</label>
		<input type="text" class="module_settings_property" name="question_id" value="$_GET['question_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="get_next_by_parent">
		<div class="get_next_object_type_id">
			<label>Object Type:</label>
			<select class="module_settings_property" name="get_next_by_parent[object_type_id]">
			</select>
		</div>
		<div class="get_next_object_id">
			<label>Object Id:</label>
			<input type="text" class="module_settings_property" name="get_next_by_parent[object_id]" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="get_next_group">
			<label>Group:</label>
			<input type="text" class="module_settings_property" name="get_next_by_parent[group]" />
		</div>
		<div class="clear"></div>
		<div class="get_next_previous_order">
			<label>Find next item based in previous order:</label>
			<input type="text" class="module_settings_property" name="get_next_by_parent[previous_order]" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
	</div>
	<div class="clear"></div>
	
	<div class="allow_multiple_answers">
		<label>Allow Multiple Answers:</label>
		<input type="checkbox" class="module_settings_property" name="allow_multiple_answers" value="1" />
	</div>
	
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"question_id" => array("type" => "label", "label" => "", "class" => "question_id", "show" => 0), 
			"title" => array("type" => "h1", "label" => "", "class" => "question_title"), 
			"description" => array("type" => "h3", "label" => "", "class" => "question_description"), 
			"created_date" => array("type" => "label", "label" => "", "class" => "question_created_date"), 
			"modified_date" => array("type" => "label", "label" => "", "class" => "question_modified_date")
		),
		"buttons" => array(
	 		"view" => true,
	 		"insert" => false,
	 		"update" => true,
	 		"delete" => true,
	 		"undefined" => true,
	 	),
	 	"css" => true,
	 	"js" => true,
	));
?>
</div>
