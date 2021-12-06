<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("comment/admin/CommentAdminUtil", $common_project_name);
	
	$CommentAdminUtil = new CommentAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$comment_id = $_GET["comment_id"];
	
	if ($comment_id) {
		$total = CommentUtil::countObjectCommentsByCommentId($brokers, $comment_id, true);
		$data = CommentUtil::getObjectCommentsByCommentId($brokers, $comment_id, $options, true);
	}
	else {
		$total = CommentUtil::countAllObjectComments($brokers, true);
		$data = CommentUtil::getAllObjectComments($brokers, $options, true);
	}
	
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	
	$pks = "comment_id=#[\$idx][comment_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Comments List" . ($comment_id ? " for comment: '" . $comment_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_comment") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_comment") . $pks,
		"fields" => array(
			"comment_id", 
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
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_comments.css" type="text/css" charset="utf-8" />';
	$menu_settings = $CommentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
