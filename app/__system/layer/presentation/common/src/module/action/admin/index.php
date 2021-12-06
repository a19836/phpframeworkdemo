<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("action/admin/ActionAdminUtil", $common_project_name);
	
	$ActionAdminUtil = new ActionAdminUtil($CommonModuleAdminUtil);
	
	$head = "";
	$menu_settings = $ActionAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getHomePageContent();
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
