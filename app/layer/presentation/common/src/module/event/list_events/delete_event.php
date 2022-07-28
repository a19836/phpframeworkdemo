<?php
include_once $EVC->getModulePath("event/EventUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (EventUtil::deleteEvent($EVC, $_GET["event_id"]) && EventUtil::deleteObjectEventsByEventId($brokers, $_GET["event_id"])) {
	echo "1";
}
?>
