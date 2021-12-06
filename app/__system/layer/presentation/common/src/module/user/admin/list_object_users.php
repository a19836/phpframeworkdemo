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
		$total = UserUtil::countObjectUsersByUserId($brokers, $user_id, true);
		$data = UserUtil::getObjectUsersByUserId($brokers, $user_id, $options, true);
	}
	else {
		$total = UserUtil::countAllObjectUsers($brokers, true);
		$data = UserUtil::getAllObjectUsers($brokers, $options, true);
	}
	
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	
	$pks = "user_id=#[\$idx][user_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Users List" . ($user_id ? " for user: '" . $user_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_user") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_user") . $pks,
		"fields" => array(
			"user_id" => array("available_values" => $available_users),
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"group", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_users.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
