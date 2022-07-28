<?php
if ($PEVC) {
	$CommonModuleAdminUtil->setHead($head);
	$CommonModuleAdminUtil->setMenuSettings($menu_settings);
	$CommonModuleAdminUtil->setContent($main_content);
	
	$CommonModuleAdminUtil->printTemplate();
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
