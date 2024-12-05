<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("menu/MenuUtil", $common_project_name);
	
	if (isset($_GET["group_id"]) && MenuUtil::deleteMenuItemsByGroupId($brokers, $_GET["group_id"]) && MenuUtil::deleteMenuGroup($brokers, $_GET["group_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
