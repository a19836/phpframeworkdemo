<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("comment/admin/CommentAdminUtil", $common_project_name);
	
	$CommentAdminUtil = new CommentAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$comment_id = $_GET["comment_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"comment_id" => $comment_id,
				"user_id" => $_POST["user_id"],
				"comment" => $_POST["comment"],
			);
			
			if ($comment_id)
				$data["object_comments"] = CommentUtil::getObjectCommentsByConditions($brokers, array("comment_id" => $comment_id), null, false, true);
			
			$status = $_POST["add"] ? CommentUtil::insertComment($brokers, $data) : CommentUtil::updateComment($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = CommentUtil::deleteComment($brokers, $comment_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Comment ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_comment") . "comment_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this comment. Please try again...";
			}
		}
	}
	
	$data = CommentUtil::getCommentsByConditions($brokers, array("comment_id" => $comment_id), null, null, true);
	$data = $data[0];
	
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Comment '$comment_id'" : "Add Comment",
		"fields" => array(
			"user_id" => $users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options),
			"comment" => "textarea",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_comment.css" type="text/css" charset="utf-8" />';
	$menu_settings = $CommentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
