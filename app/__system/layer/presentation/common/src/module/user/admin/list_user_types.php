<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "user_type_id=#[\$idx][user_type_id]#";
	
	$list_settings = array(
		"title" => "User Types List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user_type") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user_type") . $pks,
		"fields" => array("user_type_id", "name", "created_date", "modified_date"),
		"data" => UserUtil::getAllUserTypes($brokers, true),
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_user_types.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
