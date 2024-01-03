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
	 	"block_class" => "quiz_answer_question",
		"fields" => array(
			"question_id" => array("type" => "label", "label" => "", "class" => "question_id", "show" => 0), 
			"title" => array("type" => "label", "label" => "", "class" => "question_title"), 
			"description" => array("type" => "label", "label" => "", "class" => "question_description"), 
			"created_date" => array("type" => "label", "label" => "", "class" => "question_created_date", "show" => 0), 
			"modified_date" => array("type" => "label", "label" => "", "class" => "question_modified_date", "show" => 0)
		),
		"buttons" => array(
	 		"view" => true,
	 		"insert" => false,
	 		"update" => true,
	 		"delete" => true,
	 		"undefined" => true,
	 	),
	 	"css" => "
.quiz_answer_question {
    margin-bottom:15px;
    padding:10px;
    border:1px solid #ccc;
    border-radius:5px;
}
.quiz_answer_question ul,
  .quiz_answer_question li {
    margin-bottom:0;
    list-style:none;
}
.quiz_answer_question.answered .question_title:after,
  .quiz_answer_question.changed .question_title:after {
    content:'answered';
    margin-top:2px;
    margin-right:10px;
    float:right;
    font-size:80%;
    color:#ccc;
}
.quiz_answer_question.changed .question_title:after {
    content:'not saved';
}
.quiz_answer_question.answered.changed .question_title:after {
    content:'answered - not saved';
}
.quiz_answer_question .question_answers {
    padding:15px 10px 0;
}
.quiz_answer_question .question_answers > li {
    line-height:normal;
    margin-bottom:10px;
}
.quiz_answer_question .question_answers input  {
    vertical-align:middle;
}
.quiz_answer_question .question_answers > li .answer_description {
	margin-left:20px;
	color:#6c757d;
}
.quiz_answer_question .question_answers > li .answer_description:not(:empty) {
	padding-bottom:10px;
}",
	 	"js" => "
$(function() {
    var question = $('.quiz_answer_question');
    
    question.find('.question_answers .answer_title input:checked').each(function(idx, input) {
        question.addClass('answered').attr('saved_answer', input.value);
    });
    
    //Do not use on change event bc if the form allows the user to unselect its option, then the on change event wont work.
    question.find('.question_answers .answer_title input').on('click', function() {
        var input = $(this);
    	
        setTimeout(function() {
    	   	var new_answer = question.find('.question_answers .answer_title input:checked').val();
    	    
    	   	if (question.attr('saved_answer') != new_answer)
    	        question.addClass('changed');
    	   	else
    	        question.removeClass('changed');
        }, 300);
    });
});",
	));
?>
</div>
