<?php
include_once $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (/*ZipUtil::deleteZipsByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteZonesByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteCitiesByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteStatesByCountryId($brokers, $_GET["country_id"]) && */ZipUtil::deleteCountry($brokers, $_GET["country_id"])) {
	echo "1";
}
?>
