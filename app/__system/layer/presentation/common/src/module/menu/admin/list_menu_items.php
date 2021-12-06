<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("menu/admin/MenuAdminUtil", $common_project_name);
	
	$MenuAdminUtil = new MenuAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$MenuAdminUtil->initMenuGroups($brokers);
	$available_groups = $MenuAdminUtil->getAvailableGroups();
	
	$MenuAdminUtil->initMenuItems($brokers);
	$available_items = $MenuAdminUtil->getAvailableItems();
	
	$group_id = $_GET["group_id"];
	
	if ($group_id) {
		$total = MenuUtil::countMenuItemsByConditions($brokers, array("group_id" => $group_id), null, true);
		$data = MenuUtil::getMenuItemsByConditions($brokers, array("group_id" => $group_id), null, $options, true);
	}
	else {
		$total = MenuUtil::countAllMenuItems($brokers, true);
		$data = MenuUtil::getAllMenuItems($brokers, $options, true);
	}
	
	$pks = "item_id=#[\$idx][item_id]#";
	
	$list_settings = array(
		"title" => "Menu Items List" . ($group_id ? " for group: '" . $available_groups[$group_id] . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_menu_item") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_menu_item") . $pks,
		"fields" => array(
			"group_id" => array("available_values" => $available_groups, "class" => $group_id ? "hidden" : ""), 
			"parent_id" => array("available_values" => $available_items), 
			"item_id", 
			"label", 
			"title", 
			"class", 
			"url", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_menu_items.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
