<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$user_id = $_GET["user_id"];
	
	if ($user_id) {
		$total = UserUtil::countUserEnvironmentsByConditions($brokers, array("user_id" => $user_id), null, true);
		$data = UserUtil::getUserEnvironmentsByConditions($brokers, array("user_id" => $user_id), null, $options, true);
	}
	else {
		$total = UserUtil::countAllUserEnvironments($brokers, true);
		$data = UserUtil::getAllUserEnvironments($brokers, $options, true);
	}
	
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	
	$pks = "user_id=#[\$idx][user_id]#&environment_id=#[\$idx][environment_id]#";
	
	$list_settings = array(
		"title" => "User Environments List" . ($user_id ? " for user: '" . $user_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user_environment") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user_environment") . $pks,
		"fields" => array(
			"user_id" => array("available_values" => $available_users),
			"environment_id",  
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_user_environments.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
