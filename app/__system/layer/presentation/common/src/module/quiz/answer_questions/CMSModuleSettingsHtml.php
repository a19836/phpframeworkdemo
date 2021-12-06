<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script>
	onUpdatePTLFromFieldsSettings = onUserAnswersUpdatePTLFromFieldsSettings;
</script>

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
	
	<div class="questions_type">
		<label>Questions Type:</label>
		<select class="module_settings_property" name="questions_type" onChange="onChangeQuestionsType(this)">
			<option value="get_by_parent">Get questions by object parent</option>
			<option value="get_by_parent_group">Get questions by object parent group</option>
		</select>
	</div>
	<div class="get_by_parent">
		<div class="questions_parent_object_type_id">
			<label>Object Type:</label>
			<select class="module_settings_property" name="object_type_id">
			</select>
		</div>
		<div class="questions_parent_object_id">
			<label>Object Id:</label>
			<input type="text" class="module_settings_property" name="object_id" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="questions_parent_group">
			<label>Group:</label>
			<input type="text" class="module_settings_property" name="group" />
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
