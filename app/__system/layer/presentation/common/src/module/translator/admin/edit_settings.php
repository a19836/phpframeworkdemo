<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include_once get_lib("org.phpframework.util.MimeTypeHandler");
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	if (!empty($_POST)) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$properties = array(
			"TEXT_TRANSLATOR_DEFAULT_LANGUAGE" => !empty($_POST["TEXT_TRANSLATOR_DEFAULT_LANGUAGE"]) ? $_POST["TEXT_TRANSLATOR_DEFAULT_LANGUAGE"] : "",
			"TEXT_TRANSLATOR_ROOT_FOLDER_PATH" => !empty($_POST["TEXT_TRANSLATOR_ROOT_FOLDER_PATH"]) ? $_POST["TEXT_TRANSLATOR_ROOT_FOLDER_PATH"] : "",
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
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_settings.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
