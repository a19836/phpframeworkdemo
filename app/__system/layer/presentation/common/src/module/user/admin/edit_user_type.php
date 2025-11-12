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
	$reserved_user_type_ids = UserUtil::getReservedUserTypeIds();
	$is_native = in_array($user_type_id, $reserved_user_type_ids);
	
	if (!empty($_POST)) {
		if ($is_native) {
			$error_message = "This user type is native and cannot be edit!";
		}
		else {
			if (!empty($_POST["add"]) || !empty($_POST["save"])) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
				$action = "save";
				
				$data = array(
					"user_type_id" => !empty($_POST["add"]) ? $_POST["user_type_id"] : $user_type_id,
					"name" => isset($_POST["name"]) ? $_POST["name"] : null,
				);
				$status = !empty($_POST["add"]) ? UserUtil::insertUserType($brokers, $data) : UserUtil::updateUserType($brokers, $data);
			}
			else if (!empty($_POST["delete"])) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
				$action = "delete";
				$status = UserUtil::deleteUserType($brokers, $user_type_id);
			}
	
			if (!empty($action)) {
				if (!empty($status)) {
					$status_message = "User type ${action}d successfully!";
			
					if (!empty($_POST["add"])) {
						$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_type") . "user_type_id=$status";
						echo "<script>alert('$status_message');document.location='$url';</script>";
						die();
					}
				}
				else {
					$error_message = "There was an error trying to $action this user type. Please try again...";
				}
			}
		}
	}
	
	$data = UserUtil::getUserTypesByConditions($brokers, array("user_type_id" => $user_type_id), null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit User Type '$user_type_id'" : "Add User Type",
		"class" => $is_native ? "native" : "",
		"fields" => array(
			"user_type_id" => $is_native || $data ? "label" : "text",
			"name" => $is_native ? "label" : "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_type.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
