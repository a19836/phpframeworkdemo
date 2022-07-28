<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = $_GET["user_id"];
	$user_type_id = $_GET["user_type_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => $_POST["user_id"],
				"user_type_id" => $_POST["user_type_id"],
			);
			$status = UserUtil::insertUserUserType($brokers, $data);
		}
		else if ($_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"old_user_id" => $user_id,
				"new_user_id" => $_POST["user_id"],
				"old_user_type_id" => $user_type_id,
				"new_user_type_id" => $_POST["user_type_id"],
			);
			$status = UserUtil::updateUserUserType($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserUserType($brokers, $user_id, $user_type_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Users User Type ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_user_type") . "user_id=${data['user_id']}&user_type_id=${data['user_type_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
				else if ($_POST["save"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_user_type") . "user_id=${data['new_user_id']}&user_type_id=${data['new_user_type_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this user user type. Please try again...";
			}
		}
	}
	
	$data = $user_id && $user_type_id ? UserUtil::getUserUserTypesByConditions($brokers, array("user_id" => $user_id, "user_type_id" => $user_type_id), null, null, true) : array();
	$data = $data[0];
	
	$UserAdminUtil->initUsers($brokers);
	$user_type_options = $UserAdminUtil->getUserTypeOptions($brokers, $data);
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User's User Type" : "Add User's User Type",
		"fields" => array(
			"user_id" => $users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options),
			"user_type_id" => array("type" => "select", "options" => $user_type_options),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_user_type.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
