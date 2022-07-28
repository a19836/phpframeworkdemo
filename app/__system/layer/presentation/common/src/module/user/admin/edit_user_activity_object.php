<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$thread_id = $_GET["thread_id"];
	$user_id = $_GET["user_id"];
	$activity_id = $_GET["activity_id"];
	$object_type_id = $_GET["object_type_id"];
	$object_id = $_GET["object_id"];
	$time = $_GET["time"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"thread_id" => $_POST["thread_id"],
				"user_id" => $_POST["user_id"],
				"activity_id" => $_POST["activity_id"],
				"object_type_id" => $_POST["object_type_id"],
				"object_id" => $_POST["object_id"],
				"time" => time(),
				"extra" => $_POST["extra"],
			);
			$status = UserUtil::insertUserActivityObject($brokers, $data);
		}
		else if ($_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"thread_id" => $thread_id,
				"user_id" => $user_id,
				"activity_id" => $activity_id,
				"object_type_id" => $object_type_id,
				"object_id" => $object_id,
				"time" => $time,
				"extra" => $_POST["extra"],
			);
			$status = UserUtil::updateUserActivityObject($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteUserActivityObject($brokers, $thread_id, $user_id, $activity_id, $object_type_id, $object_id, $time);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "User Activity Object ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_user_activity_object") . "thread_id=${data['thread_id']}&user_id=${data['user_id']}&activity_id=${data['activity_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}&time=${data['time']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this user activity object. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getUserActivityObjectsByConditions($brokers, array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), null, null, true);
	$data = $data[0];
	
	$UserAdminUtil->initUsers($brokers);
	$activity_options = $UserAdminUtil->getActivityOptions($data);
	$available_activities = $UserAdminUtil->getAvailableActivities();
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	$available_users = $users_limit_exceeded ? null : $CommonModuleAdminUtil->getAvailableUsers($brokers);
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit User Activity Object" : "Add User Activity Object",
		"fields" => array(
			"thread_id" => $data ? "label" : "text",
			"user_id" => $data ? array("type" => "label", "available_values" => $available_users) : ($users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options)),
			"activity_id" => $data ? array("type" => "label", "available_values" => $available_activities) : array("type" => "select", "options" => $activity_options),
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"time" => $data ? "label" : "hidden",
			"extra" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_user_activity_object.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
