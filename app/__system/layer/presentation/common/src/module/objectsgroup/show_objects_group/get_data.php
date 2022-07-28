<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
	include $EVC->getModulePath("common/CommonModuleSettingsUtil", $common_project_name);

	$objects_groups = ObjectsGroupUtil::getAllObjectsGroups($PEVC, $brokers, false, true);
	$object_types = CommonModuleSettingsUtil::getAllObjectTypes($EVC);
	
	if ($objects_groups) {
		$t = count($objects_groups);
		for ($i = 0; $i < $t; $i++)
			$objects_groups[$i]["object"] = json_encode($objects_groups[$i]["object"]);
	}
	
	$data = array("objects_groups" => $objects_groups, "object_types" => $object_types);
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

echo json_encode($data);
?>
