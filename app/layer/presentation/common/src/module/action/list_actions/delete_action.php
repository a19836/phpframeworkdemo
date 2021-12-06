<?php
include_once $EVC->getModulePath("action/ActionUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (ActionUtil::deleteAction($brokers, $_GET["action_id"])) {
	echo "1";
}
?>
