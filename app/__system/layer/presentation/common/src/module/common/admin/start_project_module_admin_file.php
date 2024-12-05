<?php
$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("common/admin/CommonModuleAdminUtil", $common_project_name);

	$CommonModuleAdminUtil = new CommonModuleAdminUtil($EVC, $bean_name, $bean_file_name, $path, $module_path, isset($_GET["popup"]) ? $_GET["popup"] : null);
}	
?>
