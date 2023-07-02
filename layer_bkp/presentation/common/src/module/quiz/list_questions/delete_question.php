<?php
include_once $EVC->getModulePath("quiz/QuizUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (QuizUtil::deleteObjectQuestionsByQuestionId($brokers, $_GET["question_id"]) && QuizUtil::deleteUserAnswersByQuestionIds($brokers, $_GET["question_id"]) && QuizUtil::deleteAnswersByQuestionId($brokers, $_GET["question_id"]) && QuizUtil::deleteQuestion($brokers, $_GET["question_id"])) {
	echo "1";
}
?>
