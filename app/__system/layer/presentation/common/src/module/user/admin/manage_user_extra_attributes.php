<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	include $EVC->getModulePath("common/admin/CommonModuleAdminTableExtraAttributesUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	$CommonModuleAdminTableExtraAttributesUtil = new CommonModuleAdminTableExtraAttributesUtil($EVC, $PEVC, $UserAuthenticationHandler, $module_path, $GLOBALS["default_db_driver"], "mu_user", "user");
	
	if ($_POST) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$CommonModuleAdminTableExtraAttributesUtil->saveData($_POST);
	}
	
	$head = $CommonModuleAdminTableExtraAttributesUtil->getHead();
	$head .= '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'manage_user_extra_attributes.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminTableExtraAttributesUtil->getContent();
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
