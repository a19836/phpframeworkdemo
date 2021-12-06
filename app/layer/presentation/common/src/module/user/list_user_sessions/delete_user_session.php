<?php
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (UserUtil::deleteUserSession($brokers, $_GET["username"], $_GET["environment_id"])) {
	echo "1";
}
?>
