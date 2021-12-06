<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("objectsgroup/admin/ObjectsGroupAdminUtil", $common_project_name);
	
	$ObjectsGroupAdminUtil = new ObjectsGroupAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "objects_group_id=#[\$idx][objects_group_id]#";
	
	$data = ObjectsGroupUtil::getAllObjectsGroups($PEVC, $brokers, false, true);
	if ($data) {
		$t = count($data);
		for ($i = 0; $i < $t; $i++)
			$data[$i]["object"] = json_encode($data[$i]["object"], true);
	}
	
	$list_settings = array(
		"title" => "Objects Groups List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_objects_group") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_objects_group") . $pks,
		"fields" => array("objects_group_id", "object", "created_date", "modified_date"),
		"total" => ObjectsGroupUtil::countAllObjectsGroups($PEVC, $brokers, true),
		"data" => $data,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_objects_groups.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ObjectsGroupAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
