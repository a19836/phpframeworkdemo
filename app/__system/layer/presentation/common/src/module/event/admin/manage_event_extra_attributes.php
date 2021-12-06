<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("event/admin/EventAdminUtil", $common_project_name);
	include $EVC->getModulePath("common/admin/CommonModuleAdminTableExtraAttributesUtil", $common_project_name);
	
	$EventAdminUtil = new EventAdminUtil($CommonModuleAdminUtil);
	$CommonModuleAdminTableExtraAttributesUtil = new CommonModuleAdminTableExtraAttributesUtil($EVC, $PEVC, $module_path, $user_global_variables_file_path, $user_beans_folder_path, $GLOBALS["default_db_driver"], "me_event", "event");
	
	if ($_POST) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$CommonModuleAdminTableExtraAttributesUtil->saveData($_POST);
	}
	
	$head = $CommonModuleAdminTableExtraAttributesUtil->getHead();
	$menu_settings = $EventAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminTableExtraAttributesUtil->getContent();
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
