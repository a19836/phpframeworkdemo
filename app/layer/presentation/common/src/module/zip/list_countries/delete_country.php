<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

include_once $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (isset($_GET["country_id"]) && /*ZipUtil::deleteZipsByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteZonesByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteCitiesByCountryId($brokers, $_GET["country_id"]) && ZipUtil::deleteStatesByCountryId($brokers, $_GET["country_id"]) && */ZipUtil::deleteCountry($brokers, $_GET["country_id"])) {
	echo "1";
}
?>
