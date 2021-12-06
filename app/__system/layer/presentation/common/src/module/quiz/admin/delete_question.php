<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
	
	if (QuizUtil::deleteUserAnswersByQuestionIds($brokers, $_GET["question_id"]) && QuizUtil::deleteAnswersByQuestionId($brokers, $_GET["question_id"]) && QuizUtil::deleteQuestion($brokers, $_GET["question_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
