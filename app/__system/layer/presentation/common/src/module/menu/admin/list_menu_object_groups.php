<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("menu/admin/MenuAdminUtil", $common_project_name);
	
	$MenuAdminUtil = new MenuAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$MenuAdminUtil->initMenuObjectGroups($brokers);
	$available_object_types = $MenuAdminUtil->getAvailableObjectTypes();
	
	$MenuAdminUtil->initMenuGroups($brokers);
	$available_groups = $MenuAdminUtil->getAvailableGroups();
	
	$group_id = $_GET["group_id"];
	
	if ($group_id) {
		$total = MenuUtil::countMenuObjectGroupsByGroupId($brokers, $group_id, true);
		$data = MenuUtil::getMenuObjectGroupsByGroupId($brokers, $group_id, $options, true);
	}
	else {
		$total = MenuUtil::countAllMenuObjectGroups($brokers, true);
		$data = MenuUtil::getAllMenuObjectGroups($brokers, $options, true);
	}
	
	$pks = "group_id=#[\$idx][group_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Menu Object Groups List" . ($group_id ? " for group: '" . $group_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_menu_object_group") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_menu_object_group") . $pks,
		"fields" => array(
			"group_id" => array("available_values" => $available_groups),
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"group", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_menu_object_groups.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
