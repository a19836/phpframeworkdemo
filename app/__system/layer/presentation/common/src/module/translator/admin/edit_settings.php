<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include_once get_lib("org.phpframework.util.MimeTypeHandler");
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	if ($_POST) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$properties = array(
			"TEXT_TRANSLATOR_DEFAULT_LANGUAGE" => $_POST["TEXT_TRANSLATOR_DEFAULT_LANGUAGE"] ? $_POST["TEXT_TRANSLATOR_DEFAULT_LANGUAGE"] : "",
			"TEXT_TRANSLATOR_ROOT_FOLDER_PATH" => $_POST["TEXT_TRANSLATOR_ROOT_FOLDER_PATH"] ? $_POST["TEXT_TRANSLATOR_ROOT_FOLDER_PATH"] : "",
		);
		
		if ($CommonModuleAdminUtil->setModuleSettings($PEVC, "translator/TranslatorSettings", $properties)) {
			$status_message = "Settings saved successfully";
		}
		else {
			$error_message = "Error trying to save new settings. Please try again...";
		}
	}
	
	$data = $CommonModuleAdminUtil->getModuleSettings($PEVC, "translator/TranslatorSettings");
	
	//Preparing HTML
	$form_settings = array(
		"title" => "Edit Settings",
		"fields" => array(
			"TEXT_TRANSLATOR_DEFAULT_LANGUAGE" => array("type" => "text", "label" => "Default Language (en/pt): "),
			"TEXT_TRANSLATOR_ROOT_FOLDER_PATH" => array("type" => "text", "label" => "Default Root Folder Path: "),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_settings.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
