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
	$parent = isset($_GET["parent"]) ? trim($_GET["parent"]) : "";
	$parent .= $parent && substr($parent, -1) != "/" ? "/" : "";
	$category = isset($_GET["category"]) ? trim($_GET["category"]) : "";
	$data = null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"category" => $parent . (isset($_POST["category"]) ? trim($_POST["category"]) : ""),
			);
			$status = TranslatorUtil::insertCategory($PEVC, $data);
		}
		else if (!empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"old_category" => $parent . $category,
				"new_category" => $parent . (isset($_POST["category"]) ? trim($_POST["category"]) : ""),
			);
			$status = TranslatorUtil::updateCategory($PEVC, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = TranslatorUtil::deleteCategory($PEVC, $parent . $category);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Category ${action}d successfully!";
				
				if (!empty($_POST["add"]) || !empty($_POST["save"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_category") . "parent=$parent&category=" . (isset($_POST["category"]) ? trim($_POST["category"]) : "");
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this category. Please try again...";
			}
		}
	}
	
	if ($category && TranslatorUtil::categoryExists($PEVC, $parent . $category))
		$data = array(
			"category" => $category,
		);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Category '$parent$category'" : "Add Category" . ($parent ? " to '$parent'" : ""),
		"fields" => array(
			"category" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_category.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
