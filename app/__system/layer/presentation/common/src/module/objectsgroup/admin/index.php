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
	include $EVC->getModulePath("objectsgroup/admin/ObjectsGroupAdminUtil", $common_project_name);
	
	$ObjectsGroupAdminUtil = new ObjectsGroupAdminUtil($CommonModuleAdminUtil);
	
	$head = "";
	$menu_settings = $ObjectsGroupAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getHomePageContent();
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
