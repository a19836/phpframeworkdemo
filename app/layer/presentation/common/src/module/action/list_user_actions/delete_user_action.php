<?php
include_once $EVC->getModulePath("action/ActionUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (isset($_GET["user_id"]) && isset($_GET["action_id"]) && isset($_GET["object_type_id"]) && isset($_GET["object_id"]) && ActionUtil::deleteUserAction($brokers, $_GET["user_id"], $_GET["action_id"], $_GET["object_type_id"], $_GET["object_id"], $_GET["time"])) {
	echo "1";
}
?>
