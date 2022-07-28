<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("menu/MenuUtil", $common_project_name);
	
	if (MenuUtil::deleteMenuItemsByGroupId($brokers, $_GET["item_id"]) && MenuUtil::deleteMenuItem($brokers, $_GET["item_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
