<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = $_GET["user_id"];
	$object_type_id = $_GET["object_type_id"];
	$object_id = $_GET["object_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => $_POST["user_id"],
				"object_type_id" => $_POST["object_type_id"],
				"object_id" => $_POST["object_id"],
				"group" => $_POST["group"],
			);
			$status = UserUtil::insertObjectUser($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteObjectUser($brokers, $user_id, $object_type_id, $object_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Object User ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_user") . "user_id=${data['user_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this object user. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getObjectUsersByConditions($brokers, array("user_id" => $user_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = $data[0];
	
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Object User" : "Add Object User",
		"fields" => array(
			"user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"group" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_user.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
