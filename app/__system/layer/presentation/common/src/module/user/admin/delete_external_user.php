<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	if (isset($_GET["external_user_id"]) && UserUtil::deleteExternalUser($brokers, $_GET["external_user_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
