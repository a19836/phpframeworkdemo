<?php
include_once $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (isset($_GET["zip_id"]) && ZipUtil::deleteZip($brokers, $_GET["zip_id"], isset($_GET["country_id"]) ? $_GET["country_id"] : null)) {
	echo "1";
}
?>
