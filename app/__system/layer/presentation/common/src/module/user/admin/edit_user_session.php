<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$username = isset($_GET["username"]) ? $_GET["username"] : null;
	$environment_id = isset($_GET["environment_id"]) ? $_GET["environment_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"username" => isset($_POST["username"]) ? strtolower($_POST["username"]) : null,
				"environment_id" => isset($_POST["environment_id"]) ? $_POST["environment_id"] : null,
				"session_id" => isset($_POST["session_id"]) ? $_POST["session_id"] : null,
				"user_id" => isset($_POST["user_id"]) ? $_POST["user_id"] : null,
				"logged_status" => isset($_POST["logged_status"]) ? $_POST["logged_status"] : null,
				"login_time" => isset($_POST["login_time"]) ? $_POST["login_time"] : null,
				"login_ip" => isset($_POST["login_ip"]) ? $_POST["login_ip"] : null,
				"logout_time" => isset($_POST["logout_time"]) ? $_POST["logout_time"] : null,
				"logout_ip" => isset($_POST["logout_ip"]) ? $_POST["logout_ip"] : null,
				"failed_login_attempts" => isset($_POST["failed_login_attempts"]) ? $_POST["failed_login_attempts"] : null,
				"failed_login_time" => isset($_POST["failed_login_time"]) ? $_POST["failed_login_time"] : null,
				"failed_login_ip" => isset($_POST["failed_login_ip"]) ? $_POST["failed_login_ip"] : null,
				"captcha" => isset($_POST["captcha"]) ? $_POST["captcha"] : null,
			);
			$status = !empty($_POST["add"]) ? UserUtil::insertUserSession($brokers, $data) : UserUtil::updateUserSession($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserSession($brokers, $username, $environment_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "User Session ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$data_username = isset($data["username"]) ? $data["username"] : null;
					$data_environment_id = isset($data["environment_id"]) ? $data["environment_id"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_session") . "username=$data_username&environment_id=$data_environment_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this user session. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUserSession($brokers, $username, $environment_id, true);
	
	$users_limit_exceeded = null;
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit User Session '$username - $environment_id'" : "Add User Session",
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
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_session.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
