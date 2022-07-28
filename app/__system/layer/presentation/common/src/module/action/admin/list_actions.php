<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("action/admin/ActionAdminUtil", $common_project_name);
	
	$ActionAdminUtil = new ActionAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "action_id=#[\$idx][action_id]#";
	
	$list_settings = array(
		"title" => "Actions List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_action") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_action") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_user_actions") . $pks),
		"fields" => array("action_id", "name", "created_date", "modified_date"),
		"data" => ActionUtil::getAllActions($brokers, true),
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_actions.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ActionAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
