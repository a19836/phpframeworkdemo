<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("menu/admin/MenuAdminUtil", $common_project_name);
	
	$MenuAdminUtil = new MenuAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$group_id = $_GET["group_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"group_id" => $group_id,
				"name" => $_POST["name"],
			);
			
			if ($group_id)
				$data["object_groups"] = MenuUtil::getMenuObjectGroupsByConditions($brokers, array("group_id" => $group_id), null, false, true);
			
			$status = $_POST["add"] ? MenuUtil::insertMenuGroup($brokers, $data) : MenuUtil::updateMenuGroup($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = MenuUtil::deleteMenuItemsByGroupId($brokers, $group_id) && MenuUtil::deleteMenuGroup($brokers, $group_id);
		}
	
		if ($action) {
			if ($status) {
				$status_message = "Menu Group ${action}d successfully!";
			
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_menu_group") . "group_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this menu group. Please try again...";
			}
		}
	}
	
	$data = MenuUtil::getMenuGroupsByConditions($brokers, array("group_id" => $group_id), null, null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Menu Group '$group_id'" : "Add Menu Group",
		"fields" => array(
			"name" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_menu_group.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
