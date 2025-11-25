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
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "group_id=#[\$idx][group_id]#";
	
	$list_settings = array(
		"title" => "Menu Groups List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_menu_group") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_menu_group") . $pks,
		"other_urls" => array(
			array("url" => $CommonModuleAdminUtil->getAdminFileUrl("list_menu_items") . $pks, "class" => "list_menu_items", "title" => "List Menu Items by Group"),
			array("url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_menu") . $pks, "class" => "manage_menu_group", "title" => "Manage Menu by Group"),
		),
		"fields" => array("group_id", "name", "created_date", "modified_date"),
		"data" => MenuUtil::getAllMenuGroups($brokers, true),
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_menu_groups.css" type="text/css" charset="utf-8" />';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
