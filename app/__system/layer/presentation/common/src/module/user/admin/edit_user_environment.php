<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = $_GET["user_id"];
	$environment_id = $_GET["environment_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => $_POST["user_id"],
				"environment_id" => $_POST["environment_id"],
			);
			$status = UserUtil::insertUserEnvironment($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserEnvironment($brokers, $user_id, $environment_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "User Environment ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_environment") . "user_id=${data['user_id']}&environment_id=${data['environment_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this user environment. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUserEnvironmentsByConditions($brokers, array("user_id" => $user_id, "environment_id" => $environment_id), null, null, true);
	$data = $data[0];
	
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User Environment" : "Add User Environment",
		"fields" => array(
			"user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"environment_id" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_environment.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
