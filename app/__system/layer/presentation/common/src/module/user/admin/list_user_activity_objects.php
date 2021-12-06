<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$data = UserUtil::getAllUserActivityObjects($brokers, $options, true);
	
	$UserAdminUtil->initUsers($brokers);
	$available_activities = $UserAdminUtil->getAvailableActivities();
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	
	$pks = "thread_id=#[\$idx][thread_id]#&user_id=#[\$idx][user_id]#&activity_id=#[\$idx][activity_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#&time=#[\$idx][time]#";
	
	$list_settings = array(
		"title" => "User Activity Objects List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user_activity_object") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user_activity_object") . $pks,
		"fields" => array(
			"thread_id", 
			"user_id" => array("available_values" => $available_users),
			"activity_id" => array("available_values" => $available_activities), 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id",
			"time",
			"extra",
			"created_date", 
			"modified_date"
		),
		"total" => UserUtil::countAllUserActivityObjects($brokers, true),
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_user_activity_objects.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
