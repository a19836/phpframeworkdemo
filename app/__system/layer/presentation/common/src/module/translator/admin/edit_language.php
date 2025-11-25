<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$category = isset($_GET["category"]) ? trim($_GET["category"]) : "";
	$language = isset($_GET["language"]) ? trim($_GET["language"]) : "";
	$data = null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"category" => $category,
				"language" => isset($_POST["language"]) ? trim($_POST["language"]) : "",
			);
			$status = TranslatorUtil::insertLanguage($PEVC, $data);
		}
		else if (!empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"category" => $category,
				"old_language" => $language,
				"new_language" => isset($_POST["language"]) ? trim($_POST["language"]) : "",
			);
			$status = TranslatorUtil::updateLanguage($PEVC, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = TranslatorUtil::deleteLanguage($PEVC, $language, $category);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Language ${action}d successfully!";
				
				if (!empty($_POST["add"]) || !empty($_POST["save"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_language") . "category=$category&language=" . (isset($_POST["language"]) ? trim($_POST["language"]) : "");
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this language. Please try again...";
			}
		}
	}
	
	if ($language && TranslatorUtil::languageExists($PEVC, $language, $category))
		$data = array(
			"language" => $language,
		);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Language '$language'" . ($category ? " in '$category'" : "") : "Add Language" . ($category ? " to '$category'" : ""),
		"fields" => array(
			"language" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_language.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings) . '
		<a class="view_category_languages" href="' . $CommonModuleAdminUtil->getAdminFileUrl("list_languages") . 'category=' . $category . '">View Category Languages</a>
	';
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
