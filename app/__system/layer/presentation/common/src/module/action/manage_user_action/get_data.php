<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("object/ObjectUtil", $common_project_name);
	include $EVC->getModulePath("action/ActionUtil", $common_project_name);
	
	$object_types = ObjectUtil::getAllObjectTypes($brokers, true);
	$actions = ActionUtil::getAllActions($brokers, true);
	
	$data = array("object_types" => $object_types, "actions" => $actions);
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

echo $data ? json_encode($data) : "";
?>
