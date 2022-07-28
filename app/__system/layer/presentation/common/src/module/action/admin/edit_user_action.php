<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("action/admin/ActionAdminUtil", $common_project_name);
	
	$ActionAdminUtil = new ActionAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = $_GET["user_id"];
	$action_id = $_GET["action_id"];
	$object_type_id = $_GET["object_type_id"];
	$object_id = $_GET["object_id"];
	$time = $_GET["time"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => $_POST["user_id"],
				"action_id" => $_POST["action_id"],
				"object_type_id" => $_POST["object_type_id"],
				"object_id" => $_POST["object_id"],
				"time" => time(),
				"value" => $_POST["value"],
			);
			$status = ActionUtil::insertUserAction($brokers, $data);
		}
		else if ($_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => $user_id,
				"action_id" => $action_id,
				"object_type_id" => $object_type_id,
				"object_id" => $object_id,
				"time" => $time,
				"value" => $_POST["value"],
			);
			$status = ActionUtil::updateUserAction($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ActionUtil::deleteUserAction($brokers, $user_id, $action_id, $object_type_id, $object_id, $time);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "User Action ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_action") . "user_id=${data['user_id']}&action_id=${data['action_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}&time=${data['time']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this user action. Please try again...";
			}
		}
	}
	
	$data = ActionUtil::getUserActionsByConditions($brokers, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), null, null, true);
	$data = $data[0];
	
	$ActionAdminUtil->initUserActions($brokers);
	$action_options = $ActionAdminUtil->getActionOptions($data);
	$available_actions = $ActionAdminUtil->getAvailableActions();
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User Action" : "Add User Action",
		"fields" => array(
			"user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"action_id" => $data ? array("type" => "label", "available_values" => $available_actions) : array("type" => "select", "options" => $action_options),
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"time" => $data ? "label" : "hidden",
			"value" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_action.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ActionAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
