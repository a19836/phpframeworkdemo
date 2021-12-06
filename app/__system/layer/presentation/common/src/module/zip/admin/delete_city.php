<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/ZipUtil", $common_project_name);
	
	if (/*ZipUtil::deleteZipsByCityId($brokers, $_GET["city_id"]) && ZipUtil::deleteZonesByCityId($brokers, $_GET["city_id"]) && */ZipUtil::deleteCity($brokers, $_GET["city_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
