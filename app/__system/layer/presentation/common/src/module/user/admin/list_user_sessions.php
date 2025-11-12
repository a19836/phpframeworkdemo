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
	
	$pks = "username=#[\$idx][username]#&environment_id=#[\$idx][environment_id]#";
	$options = isset($options) ? $options : null;
	
	$list_settings = array(
		"title" => "User Sessions List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user_session") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user_session") . $pks,
		"fields" => array("username", "environment_id", "session_id", "user_id", "logged_status", "login_time", "login_ip", "logout_time", "logout_ip", "failed_login_attempts", "failed_login_time", "failed_login_ip", "captcha", "created_date", "modified_date"),
		"total" => UserUtil::countAllUserSessions($brokers, true),
		"data" => UserUtil::getAllUserSessions($brokers, $options, true),
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_user_sessions.css" type="text/css" charset="utf-8" />';
	$menu_settings = $UserAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
