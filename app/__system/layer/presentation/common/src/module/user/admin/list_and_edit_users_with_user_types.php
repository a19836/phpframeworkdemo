<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "user_id=#[\$idx][user_id]#";
	
	$list_settings = array(
		"title" => "List and Edit Users",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user") . $pks,
		"fields" => array(
			"user_id", 
			"username", 
			"email", 
			"name", 
			"created_date", 
			"modified_date"
		),
		"total" => UserUtil::countAllUsers($brokers, true),
		"data" => UserUtil::getAllUsers($brokers, $options, true),
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_users.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
