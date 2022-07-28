<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$data = UserUtil::getAllUserUserTypes($brokers, $options, true);
	
	$UserAdminUtil->initUsers($brokers);
	$available_user_types = $UserAdminUtil->getAvailableUserTypes();
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	
	$pks = "user_id=#[\$idx][user_id]#&user_type_id=#[\$idx][user_type_id]#";
	
	$list_settings = array(
		"title" => "Users' User Types List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user_user_type") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user_user_type") . $pks,
		"fields" => array(
			"user_id" => array("available_values" => $available_users),
			"user_type_id" => array("available_values" => $available_user_types), 
			"created_date", 
			"modified_date"
		),
		"total" => UserUtil::countAllUserUserTypes($brokers, true),
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_user_user_types.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
