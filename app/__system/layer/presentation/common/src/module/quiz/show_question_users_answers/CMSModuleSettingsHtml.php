<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script>
	onUpdatePTLFromFieldsSettings = onUserAnswersUpdatePTLFromFieldsSettings;
</script>

<div class="edit_settings">
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
	</div>
	<div class="clear"></div>
	
<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => "show_question_users_answers",
		"fields" => array(
			"question_id" => array("type" => "label", "label" => "", "class" => "question_id", "show" => 0), 
			"title" => array("type" => "label", "label" => "", "class" => "question_title"), 
			"description" => array("type" => "label", "label" => "", "class" => "question_description"), 
			"created_date" => array("type" => "label", "label" => "", "class" => "question_created_date", "show" => 0), 
			"modified_date" => array("type" => "label", "label" => "", "class" => "question_modified_date", "show" => 0)
		),
		"buttons" => false,
	 	"css" => "
.show_question_users_answers table {
    margin:0;
}
.show_question_users_answers table .answer {
    vertical-align:top;
    text-align:center;
    font-weight:700;
}
.show_question_users_answers table .answer.selected {
	font-family: 'Glyphicons Halflings';
    font-size: 0;
    color:green;
}
.show_question_users_answers table .answer.selected:before {
    content: \"\\\\e013\";
    font-size:13px;
}
.show_question_users_answers table .answer .answer_description {
    font-weight:normal;
    font-size:90%;
}
.show_question_users_answers table .user {
    font-size:80%;
}",
	 	"js" => true,
	));
?>
</div>
