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
	include $EVC->getModulePath("tag/admin/TagAdminUtil", $common_project_name);
	
	$TagAdminUtil = new TagAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$tag_id = isset($_GET["tag_id"]) ? $_GET["tag_id"] : null;
	$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
	$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"tag_id" => isset($_POST["tag_id"]) ? $_POST["tag_id"] : null,
				"object_type_id" => isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null,
				"object_id" => isset($_POST["object_id"]) ? $_POST["object_id"] : null,
			);
			$status = TagUtil::insertObjectTag($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = TagUtil::deleteObjectTag($brokers, $tag_id, $object_type_id, $object_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Object Tag ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_tag") . "tag_id=${data['tag_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this user tag. Please try again...";
			}
		}
	}
	
	$data = TagUtil::getObjectTagsByConditions($brokers, array("tag_id" => $tag_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	$TagAdminUtil->initObjectTags($brokers);
	$available_object_types = $TagAdminUtil->getAvailableObjectTypes();
	$object_type_options = $TagAdminUtil->getObjectTypeOptions();
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Object Tag" : "Add Object Tag",
		"fields" => array(
			"tag_id" => $data ? "label" : "text",
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_tag.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TagAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
