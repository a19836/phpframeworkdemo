<?php
include_once $EVC->getModulePath("quiz/QuizUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (isset($_GET["user_id"]) && isset($_GET["answer_id"]) && QuizUtil::deleteUserAnswer($brokers, $_GET["user_id"], $_GET["answer_id"])) {
	echo "1";
}
?>
