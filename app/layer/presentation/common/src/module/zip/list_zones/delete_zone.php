<?php
include_once $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (/*ZipUtil::deleteZipsByZoneId($brokers, $_GET["zone_id"]) && */ZipUtil::deleteZone($brokers, $_GET["zone_id"])) {
	echo "1";
}
?>
