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
	$user_type_id = isset($_GET["user_type_id"]) ? $_GET["user_type_id"] : null;
	$activity_id = isset($_GET["activity_id"]) ? $_GET["activity_id"] : null;
	$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
	$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_type_id" => isset($_POST["user_type_id"]) ? $_POST[""] : null,
				"activity_id" => isset($_POST["activity_id"]) ? $_POST["activity_id"] : null,
				"object_type_id" => isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null,
				"object_id" => isset($_POST["object_id"]) ? $_POST["object_id"] : null,
			);
			$status = UserUtil::insertUserTypeActivityObject($brokers, $data);
		}
		else if (!empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"old_user_type_id" => $user_type_id,
				"new_user_type_id" => isset($_POST["user_type_id"]) ? $_POST["user_type_id"] : null,
				"old_activity_id" => $activity_id,
				"new_activity_id" => isset($_POST["activity_id"]) ? $_POST["activity_id"] : null,
				"old_object_type_id" => $object_type_id,
				"new_object_type_id" => isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null,
				"old_object_id" => $object_id,
				"new_object_id" => isset($_POST["object_id"]) ? $_POST["object_id"] : null,
			);
			$status = UserUtil::updateUserTypeActivityObject($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserTypeActivityObject($brokers, $user_type_id, $activity_id, $object_type_id, $object_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "User Type Activity Object ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$data_user_type_id = isset($data["user_type_id"]) ? $data["user_type_id"] : null;
					$data_activity_id = isset($data["activity_id"]) ? $data["activity_id"] : null;
					$data_object_type_id = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
					$data_object_id = isset($data["object_id"]) ? $data["object_id"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_type_activity_object") . "user_type_id=$data_user_type_id&activity_id=$data_activity_id&object_type_id=$data_object_type_id&object_id=$data_object_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
				else if (!empty($_POST["save"])) {
					$data_new_user_type_id = isset($data["new_user_type_id"]) ? $data["new_user_type_id"] : null;
					$data_new_activity_id = isset($data["new_activity_id"]) ? $data["new_activity_id"] : null;
					$data_new_object_type_id = isset($data["new_object_type_id"]) ? $data["new_object_type_id"] : null;
					$data_new_object_id = isset($data["new_object_id"]) ? $data["new_object_id"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_type_activity_object") . "user_type_id=$data_new_user_type_id&activity_id=$data_new_activity_id&object_type_id=$data_new_object_type_id&object_id=$data_new_object_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this user type activity object. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUserTypeActivityObjectsByConditions($brokers, array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	$UserAdminUtil->initUsers($brokers);
	$user_type_options = $UserAdminUtil->getUserTypeOptions($data);
	$activity_options = $UserAdminUtil->getActivityOptions($data);
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit User Type Activity Object" : "Add User Type Activity Object",
		"fields" => array(
			"user_type_id" => array("type" => "select", "options" => $user_type_options),
			"activity_id" => array("type" => "select", "options" => $activity_options),
			"object_type_id" => array("type" => "select", "options" => $object_type_options),
			"object_id" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_type_activity_object.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
