<?php
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (isset($_GET["user_id"]) && isset($_GET["user_type_id"]) && UserUtil::deleteUserUserType($brokers, $_GET["user_id"], $_GET["user_type_id"])) {
	echo "1";
}
?>
