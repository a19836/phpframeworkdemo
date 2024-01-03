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
	 	"block_class" => "quiz_answer_questions",
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
.quiz_answer_questions .question {
    margin-bottom:15px;
    padding:10px;
    border:1px solid #ccc;
    border-radius:5px;
}
.quiz_answer_questions .question.active {
    transition:opacity 1s ease-in;
}
.quiz_answer_questions .question:not(.active) {
	width:0;
	height:0;
	margin:0;
	padding:0;
	overflow:hidden;
	opacity:0;
}
.quiz_answer_questions .question ul,
  .quiz_answer_questions .question li {
    margin-bottom:0;
    list-style:none;
}
.quiz_answer_questions .question.answered .question_title:after,
  .quiz_answer_questions .question.changed .question_title:after {
    content:'answered';
    margin-top:2px;
    margin-right:10px;
    float:right;
    font-size:80%;
    color:#ccc;
}
.quiz_answer_questions .question.changed .question_title:after {
    content:'not saved';
}
.quiz_answer_questions .question.answered.changed .question_title:after {
    content:'answered - not saved';
}
.quiz_answer_questions .question .question_answers {
    padding:15px 10px 0;
}
.quiz_answer_questions .question .question_answers > li:not(.wizard-buttons) {
    line-height:normal;
    margin-bottom:10px;
}
.quiz_answer_questions .question .question_answers input  {
    vertical-align:middle;
}
.quiz_answer_questions .question .question_answers > li .answer_description {
	margin-left:20px;
	color:#6c757d;
}
.quiz_answer_questions .question .question_answers > li .answer_description:not(:empty) {
	padding-bottom:10px;
}

.quiz_answer_questions .form_fields .wizard-buttons {
    height:40px;
    margin:10px 0 0;
}
.quiz_answer_questions .form_fields .wizard-buttons .forward {
    float:right;
}
.quiz_answer_questions .form_fields .wizard-buttons .disabled,
  .quiz_answer_questions .form_fields .wizard-buttons :disabled {
    opacity:.3;
}

.quiz_answer_questions:not(.last_question) .buttons {
    display:none;
}",
	 	"js" => "document.write(unescape(\"%3Cscript src='{\$project_common_url_prefix}module/quiz/vendor/jquery-wizard/jquery-wizard.min.js' type='text/javascript'%3E%3C/script%3E\"));
    
$(function() {
    var quiz_answer_questions = $('.quiz_answer_questions');
    var ff = quiz_answer_questions.find('.form_fields');
    var questions = ff.children('.question');
    
    if (questions.length <= 1)
        quiz_answer_questions.addClass('last_question');
    
    //questions.not(':first-of-type').find('.question_answers').hide();
    questions.find('.question_answers .answer_title input:checked').each(function(idx, input) {
        $(input).parent().closest('.question').addClass('answered').attr('saved_answer', input.value);
    });
    
    //Do not use on change event bc if the form allows the user to unselect its option, then the on change event wont work.
    questions.find('.question_answers .answer_title input').on('click', function() {
        var input = $(this);
    	
        setTimeout(function() {
    	   	var question = input.parent().closest('.question');
    	   	var new_answer = question.find('.question_answers .answer_title input:checked').val();
    	    
    	   	if (question.attr('saved_answer') != new_answer)
    	        question.addClass('changed');
    	   	else
    	        question.removeClass('changed');
        }, 300);
    });
    
    //questions.find('.question_title').click(function() {
    //    var question = $(this).parent().closest('.question');
    //    question.toggleClass('toggled');
    //    question.children('.question_answers').toggle('slow');
    //});
    
    ff.wizard({
        buttonsAppendTo: '.question .question_answers',
        step: '.question > .question_title',
        getPane: function(index, step) {
            return this.\\\$element.children('.question').eq(index);
        },
        templates: {
            buttons: function buttons() {
              var options = this.options;
              return '<li class=\"wizard-buttons\"><a class=\"btn btn-secondary btn-outline back\" href=\"#' + this.id + '\" data-wizard=\"back\" role=\"button\">' + options.buttonLabels.back + '</a><a class=\"btn btn-primary btn-outline pull-xs-right forward\" href=\"#' + this.id + '\" data-wizard=\"next\" role=\"button\">' + options.buttonLabels.next + '</a></li>';
            }
        },
        buttonLabels: {
            next: 'Next',
            back: 'Previous',
        },
        onNext: function(from_elm, to_elm) {
            //from_elm.\\\$pane.children('.question_answers').hide('slow');
            //to_elm.\\\$pane.children('.question_answers').show('slow');
            
            if (to_elm.\\\$pane.next('.question').length == 0)
                quiz_answer_questions.addClass('last_question');
        },
        onBack: function(from_elm, to_elm) {
            //from_elm.\\\$pane.children('.question_answers').hide('slow');
            //to_elm.\\\$pane.children('.question_answers').show('slow');
              
            quiz_answer_questions.removeClass('last_question');
        },
    });
    
    questions.first().find('.wizard-buttons .back').remove();
    questions.last().find('.wizard-buttons .forward').remove();
    
    if (questions.length == 1)
        questions.find('.wizard-buttons').remove();
});",
	));
?>
</div>
