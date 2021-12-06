<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("object/admin/ObjectAdminUtil", $common_project_name);
	
	$ObjectAdminUtil = new ObjectAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "object_type_id=#[\$idx][object_type_id]#";
	
	$list_settings = array(
		"title" => "Object Types List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_type") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_type") . $pks,
		"fields" => array("object_type_id", "name", "created_date", "modified_date"),
		"data" => ObjectUtil::getAllObjectTypes($brokers, true),
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_types.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ObjectAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
