<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/ZipUtil", $common_project_name);
	
	if (/*ZipUtil::deleteZipsByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteZonesByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteCitiesByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteStatesByCountryId($brokers, $_GET["country_id"]) && */ZipUtil::deleteCountry($brokers, $_GET["country_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
