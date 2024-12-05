<?php
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
