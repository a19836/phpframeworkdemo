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
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
	
			$data = array(
				"user_id" => $user_id,
				"user_type_id" => isset($_POST["user_type_id"]) ? $_POST["user_type_id"] : null,
				"username" => isset($_POST["username"]) ? strtolower($_POST["username"]) : null,
				"password" => isset($_POST["password"]) ? $_POST["password"] : null,
				"email" => isset($_POST["email"]) ? strtolower($_POST["email"]) : null,
				"name" => isset($_POST["name"]) ? $_POST["name"] : null,
				"security_question_1" => isset($_POST["security_question_1"]) ? $_POST["security_question_1"] : null,
				"security_answer_1" => isset($_POST["security_answer_1"]) ? $_POST["security_answer_1"] : null,
				"security_question_2" => isset($_POST["security_question_2"]) ? $_POST["security_question_2"] : null,
				"security_answer_2" => isset($_POST["security_answer_2"]) ? $_POST["security_answer_2"] : null,
				"security_question_3" => isset($_POST["security_question_3"]) ? $_POST["security_question_3"] : null,
				"security_answer_3" => isset($_POST["security_answer_3"]) ? $_POST["security_answer_3"] : null,
				"do_not_encrypt_password" => isset($_POST["do_not_encrypt_password"]) ? $_POST["do_not_encrypt_password"] : null,
			);
			
			if (!empty($_POST["add"])) {
				$status = UserUtil::insertUser($PEVC, $data, $brokers);
			}
			else {
				if ($user_id) {
					$data["object_users"] = UserUtil::getObjectUsersByConditions($brokers, array("user_id" => $user_id), null, false, true);
					$data["user_environments"] = UserUtil::getUserEnvironmentsByConditions($brokers, array("user_id" => $user_id), null, false, true);
				}
				
				if (UserUtil::updateUser($PEVC, $data, $brokers))
					$status = isset($_POST["password"]) && strlen($_POST["password"]) ? UserUtil::updateUserPassword($brokers, $data) : true;//only update password if exists any change
			}
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUser($PEVC, $user_id, $brokers);
		}

		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "User ${action}d successfully!";
		
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user") . "user_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this user. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUsersByConditions($brokers, array("user_id" => $user_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	unset($data["password"]);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit User '$user_id'" : "Add User",
		"fields" => array(
			"username" => "text",
			"password" => "password",
			"email" => "text",
			"name" => "text",
			"security_question_1" => "text",
			"security_answer_1" => "text",
			"security_question_2" => "text",
			"security_answer_2" => "text",
			"security_question_3" => "text",
			"security_answer_3" => "text",
			"do_not_encrypt_password" => array("type" => "checkbox", "label" => "Do not encrypt the user password:", "next_html" => '<div class="info">(This means that the passwords will not be encrypted in the DB and the Sysadmin can see it by accessing directly the mu_user table in the DB)</div>'),
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
