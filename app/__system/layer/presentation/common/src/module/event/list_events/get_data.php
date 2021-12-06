<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("object/ObjectUtil", $common_project_name);
	include $EVC->getModulePath("event/EventUtil", $common_project_name);
	
	$object_types = ObjectUtil::getAllObjectTypes($brokers, true);
	$events = EventUtil::getAllEvents($brokers, false, true);
	
	$data = array("object_types" => $object_types, "events" => $events);
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

echo $data ? json_encode($data) : "";
?>
