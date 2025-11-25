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
	include $EVC->getModulePath("menu/admin/MenuAdminUtil", $common_project_name);
	
	$MenuAdminUtil = new MenuAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$item_id = isset($_GET["item_id"]) ? $_GET["item_id"] : null;
	$data = null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"item_id" => $item_id,
				"group_id" => isset($_POST["group_id"]) ? $_POST["group_id"] : null,
				"parent_id" => isset($_POST["parent_id"]) ? $_POST["parent_id"] : null,
				"label" => isset($_POST["label"]) ? $_POST["label"] : null,
				"title" => isset($_POST["title"]) ? $_POST["title"] : null,
				"class" => isset($_POST["class"]) ? $_POST["class"] : null,
				"url" => isset($_POST["url"]) ? $_POST["url"] : null,
				"previous_html" => isset($_POST["previous_html"]) ? $_POST["previous_html"] : null,
				"next_html" => isset($_POST["next_html"]) ? $_POST["next_html"] : null,
				"order" => isset($_POST["order"]) ? $_POST["order"] : null,
			);
			$status = !empty($_POST["add"]) ? MenuUtil::insertMenuItem($brokers, $data) : MenuUtil::updateMenuItem($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = MenuUtil::deleteMenuItemsByGroupId($brokers, $item_id) && MenuUtil::deleteMenuItem($brokers, $item_id);
		}
	
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Menu Item ${action}d successfully!";
			
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_menu_item") . "item_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this menu item. Please try again...";
			}
		}
	}
	
	if ($item_id) {
		$data = MenuUtil::getMenuItemsByConditions($brokers, array("item_id" => $item_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	}
	
	$default_data = array(
		//"group_id" => 0,
		//"parent_id" => 0,
		"order" => 0,
	);
		
	$MenuAdminUtil->initMenuGroups($brokers);
	$group_options = $MenuAdminUtil->getGroupOptions();
	
	$MenuAdminUtil->initMenuItems($brokers);
	$item_options = $MenuAdminUtil->getItemOptions();
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Menu Item '$item_id'" : "Add Menu Item",
		"fields" => array(
			"group_id" => array("type" => "select", "options" => $group_options),
			"parent_id" => array("type" => "select", "options" => $item_options),
			"label" => "text",
			"title" => "text",
			"class" => "text",
			"url" => "text",
			"previous_html" => "textarea",
			"next_html" => "textarea",
			"order" => "number",
		),
		"data" => $data,
		"default_data" => $default_data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_menu_item.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
