<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("comment/admin/CommentAdminUtil", $common_project_name);
	
	$CommentAdminUtil = new CommentAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$comment_id = isset($_GET["comment_id"]) ? $_GET["comment_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"comment_id" => $comment_id,
				"user_id" => isset($_POST["user_id"]) ? $_POST["user_id"] : null,
				"comment" => isset($_POST["comment"]) ? $_POST["comment"] : null,
			);
			
			if ($comment_id)
				$data["object_comments"] = CommentUtil::getObjectCommentsByConditions($brokers, array("comment_id" => $comment_id), null, false, true);
			
			$status = !empty($_POST["add"]) ? CommentUtil::insertComment($brokers, $data) : CommentUtil::updateComment($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = CommentUtil::deleteComment($brokers, $comment_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Comment ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_comment") . "comment_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this comment. Please try again...";
			}
		}
	}
	
	$data = CommentUtil::getCommentsByConditions($brokers, array("comment_id" => $comment_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	$users_limit_exceeded = null;
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Comment '$comment_id'" : "Add Comment",
		"fields" => array(
			"user_id" => $users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options),
			"comment" => "textarea",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_comment.css" type="text/css" charset="utf-8" />';
	$menu_settings = $CommentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
