<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$external_user_id = $_GET["external_user_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"external_user_id" => $external_user_id,
				"user_id" => $_POST["user_id"],
				"external_type_id" => $_POST["external_type_id"],
				"social_network_type" => $_POST["social_network_type"],
				"social_network_user_id" => $_POST["social_network_user_id"],
				"token_1" => $_POST["token_1"],
				"token_2" => $_POST["token_2"],
				"token_3" => $_POST["token_3"],
				"data" => $_POST["data"],
			);
			$status = $_POST["add"] ? UserUtil::insertExternalUser($brokers, $data) : UserUtil::updateExternalUser($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = UserUtil::deleteExternalUser($brokers, $external_user_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "External User ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_external_user") . "external_user_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this external user. Please try again...";
			}
		}
	}
	
	$data = UserUtil::getExternalUser($brokers, $external_user_id, true);
	
	$user_options = $CommonModuleAdminUtil->getUserOptions($brokers, $data, $users_limit_exceeded);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit External User '$external_user_id'" : "Add External User",
		"fields" => array(
			"user_id" => $users_limit_exceeded ? "text" : array("type" => "select", "options" => $user_options),
			"external_type_id" => array("type" => "select", "options" => array(
				array("value" => "", "label" => ""),
				array("value" => 0, "label" => "Auth 0"),
			)),
			"social_network_type" => "text",
			"social_network_user_id" => "text",
			"token_1" => "textarea",
			"token_2" => "textarea",
			"token_3" => "textarea",
			"data" => "textarea",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_external_user.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
