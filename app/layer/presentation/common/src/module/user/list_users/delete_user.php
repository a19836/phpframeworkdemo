<?php
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (UserUtil::deleteUser($EVC, $_GET["user_id"], $brokers)) {
	echo "1";
}
?>
