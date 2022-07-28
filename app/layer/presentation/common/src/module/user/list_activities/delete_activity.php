<?php
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();
$reserved_activity_ids = UserUtil::getReservedActivityIds();

if (in_array($_GET["activity_id"], $reserved_activity_ids)) {
	echo "This activity is native and cannot be deleted!";
}
else if (UserUtil::deleteActivity($brokers, $_GET["activity_id"])) {
	echo "1";
}
?>
