<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

if (!empty($PEVC)) {
	$head = isset($head) ? $head : null;
	$menu_settings = isset($menu_settings) ? $menu_settings : null;
	$main_content = isset($main_content) ? $main_content : null;
	
	$CommonModuleAdminUtil->setHead($head);
	$CommonModuleAdminUtil->setMenuSettings($menu_settings);
	$CommonModuleAdminUtil->setContent($main_content);
	
	$CommonModuleAdminUtil->printTemplate();
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
