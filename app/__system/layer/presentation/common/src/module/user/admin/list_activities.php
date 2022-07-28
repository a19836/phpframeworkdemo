<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "activity_id=#[\$idx][activity_id]#";
	
	$list_settings = array(
		"title" => "Activities List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_activity") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_activity") . $pks,
		"fields" => array("activity_id", "name", "created_date", "modified_date"),
		"data" => UserUtil::getAllActivities($brokers, true),
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_activities.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
