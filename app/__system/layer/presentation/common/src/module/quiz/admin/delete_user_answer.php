<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
	
	if (QuizUtil::deleteUserAnswer($brokers, $_GET["user_id"], $_GET["answer_id"]))
		echo "1";
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
