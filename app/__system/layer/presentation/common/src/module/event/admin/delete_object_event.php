<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("event/EventUtil", $common_project_name);
	
	if (isset($_GET["event_id"]) && isset($_GET["object_type_id"]) && isset($_GET["object_id"]) && EventUtil::deleteObjectEvent($brokers, $_GET["event_id"], $_GET["object_type_id"], $_GET["object_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
