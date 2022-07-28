<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$username = $_GET["username"];
	$environment_id = $_GET["environment_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"username" => strtolower($_POST["username"]),
				"environment_id" => $_POST["environment_id"],
				"session_id" => $_POST["session_id"],
				"user_id" => $_POST["user_id"],
				"logged_status" => $_POST["logged_status"],
				"login_time" => $_POST["login_time"],
				"login_ip" => $_POST["login_ip"],
				"logout_time" => $_POST["logout_time"],
				"logout_ip" => $_POST["logout_ip"],
				"failed_login_attempts" => $_POST["failed_login_attempts"],
				"failed_login_time" => $_POST["failed_login_time"],
				"failed_login_ip" => $_POST["failed_login_ip"],
				"captcha" => $_POST["captcha"],
			);
			$status = $_POST["add"] ? UserUtil::insertUserSession($brokers, $data) : UserUtil::updateUserSession($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserSession($brokers, $username, $environment_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "User Session ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_session") . "username=${data['username']}&environment_id=${data['environment_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this user session. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUserSession($brokers, $username, $environment_id, true);
	
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User Session '$username - $environment_id'" : "Add User Session",
		"fields" => array(
			"username" => "text",
			"environment_id" => "text",
			"session_id" => "text",
			"user_id" => $users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options),
			"logged_status" => array("type" => "select", "options" => array(
				array("value" => 0, "label" => "Not Logged"),
				array("value" => 1, "label" => "Logged"),
			)),
			"login_time" => "text",
			"login_ip" => "text",
			"logout_time" => "text",
			"logout_ip" => "text",
			"failed_login_attempts" => "text",
			"failed_login_time" => "text",
			"failed_login_ip" => "text",
			"captcha" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_session.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
