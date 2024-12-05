<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
	$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
	$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_id" => isset($_POST["user_id"]) ? $_POST["user_id"] : null,
				"object_type_id" => isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null,
				"object_id" => isset($_POST["object_id"]) ? $_POST["object_id"] : null,
				"group" => isset($_POST["group"]) ? $_POST["group"] : null,
			);
			$status = UserUtil::insertObjectUser($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteObjectUser($brokers, $user_id, $object_type_id, $object_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Object User ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$data_user_id = isset($data["user_id"]) ? $data["user_id"] : null;
					$data_object_type_id = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
					$data_object_id = isset($data["object_id"]) ? $data["object_id"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_user") . "user_id=$data_user_id&object_type_id=$data_object_type_id&object_id=$data_object_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this object user. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getObjectUsersByConditions($brokers, array("user_id" => $user_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	$users_limit_exceeded = null;
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Object User" : "Add Object User",
		"fields" => array(
			"user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"group" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_user.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
