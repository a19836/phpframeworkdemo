<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	include_once $EVC->getModulePath("user/ManageUserTypeActivityObjectsUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'manage_user_type_activity_objects.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'manage_user_type_activity_objects.js"></script>';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = ManageUserTypeActivityObjectsUtil::getHtml($PEVC);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
