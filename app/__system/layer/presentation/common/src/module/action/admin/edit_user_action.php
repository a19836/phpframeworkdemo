<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("action/admin/ActionAdminUtil", $common_project_name);
	
	$ActionAdminUtil = new ActionAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
	$action_id = isset($_GET["action_id"]) ? $_GET["action_id"] : null;
	$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
	$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
	$time = isset($_GET["time"]) ? $_GET["time"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => isset($_POST["user_id"]) ? $_POST["user_id"] : null,
				"action_id" => isset($_POST["action_id"]) ? $_POST["action_id"] : null,
				"object_type_id" => isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null,
				"object_id" => isset($_POST["object_id"]) ? $_POST["object_id"] : null,
				"time" => time(),
				"value" => isset($_POST["value"]) ? $_POST["value"] : null,
			);
			$status = ActionUtil::insertUserAction($brokers, $data);
		}
		else if (!empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => $user_id,
				"action_id" => $action_id,
				"object_type_id" => $object_type_id,
				"object_id" => $object_id,
				"time" => $time,
				"value" => isset($_POST["value"]) ? $_POST["value"] : null,
			);
			$status = ActionUtil::updateUserAction($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ActionUtil::deleteUserAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $time);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "User Action ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$data_user_id = isset($data["user_id"]) ? $data["user_id"] : null;
					$data_action_id = isset($data["action_id"]) ? $data["action_id"] : null;
					$data_object_type_id = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
					$data_object_id = isset($data["object_id"]) ? $data["object_id"] : null;
					$data_time = isset($data["time"]) ? $data["time"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_action") . "user_id=$data_user_id&action_id=$data_action_id&object_type_id=$data_object_type_id&object_id=$data_object_id&time=$data_time";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this user action. Please try again...";
			}
		}
	}
	
	$data = ActionUtil::getUserActionsByConditions($brokers, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	$ActionAdminUtil->initUserActions($brokers);
	$action_options = $ActionAdminUtil->getActionOptions($data);
	$available_actions = $ActionAdminUtil->getAvailableActions();
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	$users_limit_exceeded = null;
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit User Action" : "Add User Action",
		"fields" => array(
			"user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"action_id" => $data ? array("type" => "label", "available_values" => $available_actions) : array("type" => "select", "options" => $action_options),
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"time" => $data ? "label" : "hidden",
			"value" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_action.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ActionAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
