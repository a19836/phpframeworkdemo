<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("objectsgroup/admin/ObjectsGroupAdminUtil", $common_project_name);
	
	$ObjectsGroupAdminUtil = new ObjectsGroupAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$ObjectsGroupAdminUtil->initObjectObjectsGroups($brokers);
	$available_object_types = $ObjectsGroupAdminUtil->getAvailableObjectTypes();
	
	$objects_group_id = $_GET["objects_group_id"];
	
	if ($objects_group_id) {
		$total = ObjectsGroupUtil::countObjectObjectsGroupsByObjectsGroupId($brokers, $objects_group_id, true);
		$data = ObjectsGroupUtil::getObjectObjectsGroupsByObjectsGroupId($brokers, $objects_group_id, $options, true);
	}
	else {
		$total = ObjectsGroupUtil::countAllObjectObjectsGroups($brokers, true);
		$data = ObjectsGroupUtil::getAllObjectObjectsGroups($brokers, $options, true);
	}
	
	$pks = "objects_group_id=#[\$idx][objects_group_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Objects Groups List" . ($objects_group_id ? " for objects group: '" . $objects_group_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_objects_group") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_objects_group") . $pks,
		"fields" => array(
			"objects_group_id", 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"group", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_objects_groups.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ObjectsGroupAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
