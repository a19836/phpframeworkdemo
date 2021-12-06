<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("menu/admin/MenuAdminUtil", $common_project_name);
	
	$MenuAdminUtil = new MenuAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$item_id = $_GET["item_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"item_id" => $item_id,
				"group_id" => $_POST["group_id"],
				"parent_id" => $_POST["parent_id"],
				"label" => $_POST["label"],
				"title" => $_POST["title"],
				"class" => $_POST["class"],
				"url" => $_POST["url"],
				"previous_html" => $_POST["previous_html"],
				"next_html" => $_POST["next_html"],
				"order" => $_POST["order"],
			);
			$status = $_POST["add"] ? MenuUtil::insertMenuItem($brokers, $data) : MenuUtil::updateMenuItem($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = MenuUtil::deleteMenuItemsByGroupId($brokers, $item_id) && MenuUtil::deleteMenuItem($brokers, $item_id);
		}
	
		if ($action) {
			if ($status) {
				$status_message = "Menu Item ${action}d successfully!";
			
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_menu_item") . "item_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this menu item. Please try again...";
			}
		}
	}
	
	if ($item_id) {
		$data = MenuUtil::getMenuItemsByConditions($brokers, array("item_id" => $item_id), null, null, true);
		$data = $data[0];
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
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Menu Item '$item_id'" : "Add Menu Item",
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
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_menu_item.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
