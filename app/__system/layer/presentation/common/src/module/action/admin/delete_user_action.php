<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("action/ActionUtil", $common_project_name);
	
	if (ActionUtil::deleteUserAction($brokers, $_GET["user_id"], $_GET["action_id"], $_GET["object_type_id"], $_GET["object_id"], $_GET["time"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
