<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
	
	if (isset($_GET["answer_id"]) && QuizUtil::deleteUserAnswersByAnswerId($brokers, $_GET["answer_id"]) && QuizUtil::deleteAnswer($brokers, $_GET["answer_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
