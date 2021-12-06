<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
	
	if (ObjectsGroupUtil::deleteObjectsGroup($PEVC, $_GET["objects_group_id"], $brokers)) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
