<?php
include_once $EVC->getModulePath("event/EventUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (EventUtil::deleteObjectEvent($brokers, $_GET["event_id"], $_GET["object_type_id"], $_GET["object_id"])) {
	echo "1";
}
?>
