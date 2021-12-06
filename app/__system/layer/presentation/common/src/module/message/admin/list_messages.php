<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("message/admin/MessageAdminUtil", $common_project_name);
	
	$MessageAdminUtil = new MessageAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$data = MessageUtil::getAllMessages($brokers, $options, true);
	
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	
	$pks = "message_id=#[\$idx][message_id]#&from_user_id=#[\$idx][from_user_id]#&to_user_id=#[\$idx][to_user_id]#";
	
	$list_settings = array(
		"title" => "Messages List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_message") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_message") . $pks,
		"fields" => array(
			"message_id", 
			"from_user_id" => array("available_values" => $available_users), 
			"to_user_id" => array("available_values" => $available_users), 
			"subject", 
			"created_date", 
			"modified_date"
		),
		"total" => MessageUtil::countAllMessages($brokers, true),
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_messages.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MessageAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
