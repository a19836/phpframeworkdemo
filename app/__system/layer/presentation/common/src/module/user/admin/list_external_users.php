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
	include $EVC->getModulePath("user/admin/UserAdminUtil", $common_project_name);
	
	$UserAdminUtil = new UserAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$options = isset($options) ? $options : null;
	$data = UserUtil::getAllExternalUsers($brokers, $options, true);
	
	$UserAdminUtil->initUsers($brokers);
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	
	$pks = "external_user_id=#[\$idx][external_user_id]#";
	
	$list_settings = array(
		"title" => "External Users List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_external_user") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_external_user") . $pks,
		"fields" => array(
			"external_user_id", 
			"user_id" => array("available_values" => $available_users), 
			"external_type_id" => array("available_values" => array(
				"" => "",
				0 => "Auth 0",
			)), 
			"social_network_type", 
			"social_network_user_id", 
			"created_date", 
			"modified_date"
		),
		"total" => UserUtil::countAllExternalUsers($brokers, true),
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_external_users.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
