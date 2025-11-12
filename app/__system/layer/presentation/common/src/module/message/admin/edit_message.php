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
	include $EVC->getModulePath("message/admin/MessageAdminUtil", $common_project_name);
	
	$MessageAdminUtil = new MessageAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$message_id = isset($_GET["message_id"]) ? $_GET["message_id"] : null;
	$from_user_id = isset($_GET["from_user_id"]) ? $_GET["from_user_id"] : null;
	$to_user_id = isset($_GET["to_user_id"]) ? $_GET["to_user_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"from_user_id" => isset($_POST["from_user_id"]) ? $_POST["from_user_id"] : null,
				"to_user_id" => isset($_POST["to_user_id"]) ? $_POST["to_user_id"] : null,
				"subject" => isset($_POST["subject"]) ? $_POST["subject"] : null,
				"content" => isset($_POST["content"]) ? $_POST["content"] : null,
			);
			$status = MessageUtil::insertMessage($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = MessageUtil::deleteMessage($brokers, $message_id, $from_user_id, $to_user_id);
		}
	
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Message ${action}d successfully!";
			
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_message") . "message_id=$status&from_user_id={$data['from_user_id']}&to_user_id={$data['to_user_id']}";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this message. Please try again...";
			}
		}
	}
	
	$data = $message_id && $from_user_id && $to_user_id ? MessageUtil::getMessagesByConditions($brokers, array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id), null, null, true) : null;
	$data = isset($data[0]) ? $data[0] : null;
	
	$users_limit_exceeded = null;
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Message from user '$from_user_id' to user '$to_user_id'" : "Add Message",
		"fields" => array(
			"message_id" => $data ? "label" : "hidden",
			"from_user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"to_user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"subject" => $data ? "label" : "text",
			"content" => $data ? "label" : "textarea",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_message.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MessageAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
