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
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
	$user_type_id = isset($_GET["user_type_id"]) ? $_GET["user_type_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => isset($_POST["user_id"]) ? $_POST["user_id"] : null,
				"user_type_id" => isset($_POST["user_type_id"]) ? $_POST["user_type_id"] : null,
			);
			$status = UserUtil::insertUserUserType($brokers, $data);
		}
		else if (!empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"old_user_id" => $user_id,
				"new_user_id" => isset($_POST["user_id"]) ? $_POST["user_id"] : null,
				"old_user_type_id" => $user_type_id,
				"new_user_type_id" => isset($_POST["user_type_id"]) ? $_POST["user_type_id"] : null,
			);
			$status = UserUtil::updateUserUserType($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserUserType($brokers, $user_id, $user_type_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Users User Type ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$data_user_id = isset($data["user_id"]) ? $data["user_id"] : null;
					$data_user_type_id = isset($data["user_type_id"]) ? $data["user_type_id"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_user_type") . "user_id=$data_user_id&user_type_id=$data_user_type_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
				else if (!empty($_POST["save"])) {
					$data_new_user_id = isset($data["new_user_id"]) ? $data["new_user_id"] : null;
					$data_new_user_type_id = isset($data["new_user_type_id"]) ? $data["new_user_type_id"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_user_type") . "user_id=$data_new_user_id&user_type_id=$data_new_user_type_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this user user type. Please try again...";
			}
		}
	}
	
	$data = $user_id && $user_type_id ? UserUtil::getUserUserTypesByConditions($brokers, array("user_id" => $user_id, "user_type_id" => $user_type_id), null, true) : array();
	$data = isset($data[0]) ? $data[0] : null;
	
	$UserAdminUtil->initUsers($brokers);
	$user_type_options = $UserAdminUtil->getUserTypeOptions($data);
	
	$users_limit_exceeded = null;
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit User's User Type" : "Add User's User Type",
		"fields" => array(
			"user_id" => $users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options),
			"user_type_id" => array("type" => "select", "options" => $user_type_options),
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_user_type.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
