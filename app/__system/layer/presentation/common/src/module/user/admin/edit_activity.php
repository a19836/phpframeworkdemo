<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$activity_id = $_GET["activity_id"];
	$reserved_activity_ids = UserUtil::getReservedActivityIds();
	$is_native = in_array($activity_id, $reserved_activity_ids);
	
	if ($_POST) {
		if ($is_native) {
			$error_message = "This activity is native and cannot be edit!";
		}
		else {
			if ($_POST["add"] || $_POST["save"]) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
				$action = "save";
		
				$data = array(
					"activity_id" => $activity_id,
					"name" => $_POST["name"],
				);
				$status = $_POST["add"] ? UserUtil::insertActivity($brokers, $data) : UserUtil::updateActivity($brokers, $data);
			}
			else if ($_POST["delete"]) {
				$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
				$action = "delete";
				$status = UserUtil::deleteActivity($brokers, $activity_id);
			}
	
			if ($action) {
				if ($status) {
					$status_message = "Activity ${action}d successfully!";
			
					if ($_POST["add"]) {
						$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_activity") . "activity_id=$status";
						die("<script>alert('$status_message');document.location='$url';</script>");
					}
				}
				else {
					$error_message = "There was an error trying to $action this activity. Please try again...";
				}
			}
		}
	}
	
	$data = UserUtil::getActivitiesByConditions($brokers, array("activity_id" => $activity_id), null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Activity '$activity_id'" : "Add Activity",
		"class" => $is_native ? "native" : "",
		"fields" => array(
			"name" => $is_native ? "label" : "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_activity.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
