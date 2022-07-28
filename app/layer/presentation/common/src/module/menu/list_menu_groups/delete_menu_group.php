<?php
include_once $EVC->getModulePath("menu/MenuUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (MenuUtil::deleteMenuItemsByGroupId($brokers, $_GET["group_id"]) && MenuUtil::deleteMenuObjectGroupsByGroupId($brokers, $_GET["group_id"]) && MenuUtil::deleteMenuGroup($brokers, $_GET["group_id"])) {
	echo "1";
}
?>
