<?php
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();
$reserved_user_type_ids = UserUtil::getReservedUserTypeIds();

if (in_array($_GET["user_type_id"], $reserved_user_type_ids)) {
	echo "This user type is native and cannot be deleted!";
}
else if (UserUtil::deleteUserType($brokers, $_GET["user_type_id"])) {
	echo "1";
}
?>
