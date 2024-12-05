<?php
include_once $EVC->getModulePath("menu/MenuUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (isset($_GET["group_id"]) && isset($_GET["object_type_id"]) && isset($_GET["object_id"]) && MenuUtil::deleteMenuObjectGroup($brokers, $_GET["group_id"], $_GET["object_type_id"], $_GET["object_id"])) {
	echo "1";
}
?>
