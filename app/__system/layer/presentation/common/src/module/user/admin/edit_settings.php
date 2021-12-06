<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	if ($_POST) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$properties = array(
			"DEFAULT_USER_SESSION_EXPIRATION_TTL" => is_numeric($_POST["DEFAULT_USER_SESSION_EXPIRATION_TTL"]) ? $_POST["DEFAULT_USER_SESSION_EXPIRATION_TTL"] : 86400,//60 secs => 1 min; 3600 secs => 1 hour; 86400 secs => 1 day.
		);
		
		if ($CommonModuleAdminUtil->setModuleSettings($PEVC, "user/UserSettings", $properties)) {
			$status_message = "Settings saved successfully";
		}
		else {
			$error_message = "Error trying to save new settings. Please try again...";
		}
	}
	
	$data = $CommonModuleAdminUtil->getModuleSettings($PEVC, "user/UserSettings");
		
	//Preparing HTML
	$form_settings = array(
		"title" => "Edit Settings",
		"fields" => array(
			"DEFAULT_USER_SESSION_EXPIRATION_TTL" => array("type" => "text", "label" => "Default login session TTL (secs): "),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_settings.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
