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
	$external_user_id = isset($_GET["external_user_id"]) ? $_GET["external_user_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"external_user_id" => $external_user_id,
				"user_id" => isset($_POST["user_id"]) ? $_POST["user_id"] : null,
				"external_type_id" => isset($_POST["external_type_id"]) ? $_POST["external_type_id"] : null,
				"social_network_type" => isset($_POST["social_network_type"]) ? $_POST["social_network_type"] : null,
				"social_network_user_id" => isset($_POST["social_network_user_id"]) ? $_POST["social_network_user_id"] : null,
				"token_1" => isset($_POST["token_1"]) ? $_POST["token_1"] : null,
				"token_2" => isset($_POST["token_2"]) ? $_POST["token_2"] : null,
				"token_3" => isset($_POST["token_3"]) ? $_POST["token_3"] : null,
				"data" => isset($_POST["data"]) ? $_POST["data"] : null,
			);
			$status = !empty($_POST["add"]) ? UserUtil::insertExternalUser($brokers, $data) : UserUtil::updateExternalUser($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteExternalUser($brokers, $external_user_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "External User ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_external_user") . "external_user_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this external user. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getExternalUser($brokers, $external_user_id, true);
	
	$users_limit_exceeded = null;
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit External User '$external_user_id'" : "Add External User",
		"fields" => array(
			"user_id" => $users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options),
			"external_type_id" => array("type" => "select", "options" => array(
				array("value" => "", "label" => ""),
				array("value" => 0, "label" => "Auth 0"),
			)),
			"social_network_type" => "text",
			"social_network_user_id" => "text",
			"token_1" => "textarea",
			"token_2" => "textarea",
			"token_3" => "textarea",
			"data" => "textarea",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_external_user.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
