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
	include $EVC->getModulePath("action/admin/ActionAdminUtil", $common_project_name);
	
	$ActionAdminUtil = new ActionAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$action_id = isset($_GET["action_id"]) ? $_GET["action_id"] : null;
	$options = isset($options) ? $options : null;
	
	if ($action_id) {
		$total = ActionUtil::countUserActionsByActionId($brokers, $action_id, true);
		$data = ActionUtil::getUserActionsByActionId($brokers, $action_id, $options, true);
	}
	else {
		$total = ActionUtil::countAllUserActions($brokers, true);
		$data = ActionUtil::getAllUserActions($brokers, $options, true);
	}
	
	$ActionAdminUtil->initUserActions($brokers);
	$available_actions = $ActionAdminUtil->getAvailableActions();
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	
	$pks = "user_id=#[\$idx][user_id]#&action_id=#[\$idx][action_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#&time=#[\$idx][time]#";
	$available_action = isset($available_actions[$action_id]) ? $available_actions[$action_id] : null;
	
	$list_settings = array(
		"title" => "User Actions List" . ($action_id ? " for action: '" . $available_action . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user_action") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user_action") . $pks,
		"fields" => array(
			"user_id" => array("available_values" => $available_users), 
			"action_id" => array("available_values" => $available_actions), 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"time", 
			"value", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_user_actions.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ActionAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
