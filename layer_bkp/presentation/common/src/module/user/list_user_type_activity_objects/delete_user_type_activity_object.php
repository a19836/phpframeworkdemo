<?php
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (UserUtil::deleteUserTypeActivityObject($brokers, $_GET["user_type_id"], $_GET["activity_id"], $_GET["object_type_id"], $_GET["object_id"])) {
	echo "1";
}
?>
