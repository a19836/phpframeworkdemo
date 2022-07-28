<?php
include_once $EVC->getModulePath("quiz/QuizUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (QuizUtil::deleteUserAnswersByAnswerId($brokers, $_GET["answer_id"]) && QuizUtil::deleteAnswer($brokers, $_GET["answer_id"])) {
	echo "1";
}
?>
