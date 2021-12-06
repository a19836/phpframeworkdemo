<?php
include_once $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (/*ZipUtil::deleteZipsByStateId($brokers, $_GET["state_id"]) && ZipUtil::deleteZonesByStateId($brokers, $_GET["state_id"]) && ZipUtil::deleteCitiesByStateId($brokers, $_GET["state_id"]) && */ZipUtil::deleteState($brokers, $_GET["state_id"])) {
	echo "1";
}
?>
