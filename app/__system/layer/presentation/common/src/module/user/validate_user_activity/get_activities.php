<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	$data = UserUtil::getAllActivities($brokers, true);
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

echo !empty($data) ? json_encode($data) : "";
?>
