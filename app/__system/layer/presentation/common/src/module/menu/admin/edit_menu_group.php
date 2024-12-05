<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("menu/admin/MenuAdminUtil", $common_project_name);
	
	$MenuAdminUtil = new MenuAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$group_id = isset($_GET["group_id"]) ? $_GET["group_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
		
			$data = array(
				"group_id" => $group_id,
				"name" => isset($_POST["name"]) ? $_POST["name"] : null,
			);
			
			if ($group_id)
				$data["object_groups"] = MenuUtil::getMenuObjectGroupsByConditions($brokers, array("group_id" => $group_id), null, false, true);
			
			$status = !empty($_POST["add"]) ? MenuUtil::insertMenuGroup($brokers, $data) : MenuUtil::updateMenuGroup($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = MenuUtil::deleteMenuItemsByGroupId($brokers, $group_id) && MenuUtil::deleteMenuGroup($brokers, $group_id);
		}
	
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Menu Group ${action}d successfully!";
			
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_menu_group") . "group_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this menu group. Please try again...";
			}
		}
	}
	
	$data = MenuUtil::getMenuGroupsByConditions($brokers, array("group_id" => $group_id), null, null, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Menu Group '$group_id'" : "Add Menu Group",
		"fields" => array(
			"name" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_menu_group.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
