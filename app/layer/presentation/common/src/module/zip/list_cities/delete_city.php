<?php
include_once $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (/*ZipUtil::deleteZipsByCityId($brokers, $_GET["city_id"]) && ZipUtil::deleteZonesByCityId($brokers, $_GET["city_id"]) && */ZipUtil::deleteCity($brokers, $_GET["city_id"])) {
	echo "1";
}
?>
