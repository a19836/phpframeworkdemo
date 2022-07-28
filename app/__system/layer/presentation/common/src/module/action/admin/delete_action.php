<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("action/ActionUtil", $common_project_name);
	
	if (ActionUtil::deleteUserActionsByActionId($brokers, $_GET["action_id"]) && ActionUtil::deleteAction($brokers, $_GET["action_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
