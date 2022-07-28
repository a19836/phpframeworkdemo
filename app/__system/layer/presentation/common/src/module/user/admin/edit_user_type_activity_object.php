<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$user_type_id = $_GET["user_type_id"];
	$activity_id = $_GET["activity_id"];
	$object_type_id = $_GET["object_type_id"];
	$object_id = $_GET["object_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"user_type_id" => $_POST["user_type_id"],
				"activity_id" => $_POST["activity_id"],
				"object_type_id" => $_POST["object_type_id"],
				"object_id" => $_POST["object_id"],
			);
			$status = UserUtil::insertUserTypeActivityObject($brokers, $data);
		}
		else if ($_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"old_user_type_id" => $user_type_id,
				"new_user_type_id" => $_POST["user_type_id"],
				"old_activity_id" => $activity_id,
				"new_activity_id" => $_POST["activity_id"],
				"old_object_type_id" => $object_type_id,
				"new_object_type_id" => $_POST["object_type_id"],
				"old_object_id" => $object_id,
				"new_object_id" => $_POST["object_id"],
			);
			$status = UserUtil::updateUserTypeActivityObject($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserTypeActivityObject($brokers, $user_type_id, $activity_id, $object_type_id, $object_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "User Type Activity Object ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_type_activity_object") . "user_type_id=${data['user_type_id']}&activity_id=${data['activity_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
				else if ($_POST["save"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_type_activity_object") . "user_type_id=${data['new_user_type_id']}&activity_id=${data['new_activity_id']}&object_type_id=${data['new_object_type_id']}&object_id=${data['new_object_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this user type activity object. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUserTypeActivityObjectsByConditions($brokers, array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = $data[0];
	
	$UserAdminUtil->initUsers($brokers);
	$user_type_options = $UserAdminUtil->getUserTypeOptions($data);
	$activity_options = $UserAdminUtil->getActivityOptions($data);
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User Type Activity Object" : "Add User Type Activity Object",
		"fields" => array(
			"user_type_id" => array("type" => "select", "options" => $user_type_options),
			"activity_id" => array("type" => "select", "options" => $activity_options),
			"object_type_id" => array("type" => "select", "options" => $object_type_options),
			"object_id" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_type_activity_object.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
