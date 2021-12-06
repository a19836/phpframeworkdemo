<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("comment/admin/CommentAdminUtil", $common_project_name);
	
	$CommentAdminUtil = new CommentAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$data = CommentUtil::getAllComments($brokers, $options, true);
	
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	
	$pks = "comment_id=#[\$idx][comment_id]#";
	
	$list_settings = array(
		"title" => "Comments List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_comment") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_comment") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_object_comments") . $pks),
		"fields" => array(
			"comment_id", 
			"user_id" => array("available_values" => $available_users), 
			"comment", 
			"created_date", 
			"modified_date"
		),
		"total" => CommentUtil::countAllComments($brokers, true),
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_comments.css" type="text/css" charset="utf-8" />';
	$menu_settings = $CommentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
