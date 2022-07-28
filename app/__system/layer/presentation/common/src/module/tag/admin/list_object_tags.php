<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("tag/admin/TagAdminUtil", $common_project_name);
	
	$TagAdminUtil = new TagAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$TagAdminUtil->initObjectTags($brokers);
	$available_object_types = $TagAdminUtil->getAvailableObjectTypes();
	
	$tag_id = $_GET["tag_id"];
	
	if ($tag_id) {
		$total = TagUtil::countObjectTagsByTagId($brokers, $tag_id, true);
		$data = TagUtil::getObjectTagsByTagId($brokers, $tag_id, $options, true);
		
		$tag = TagUtil::getTagsByConditions($brokers, array("tag_id" => $tag_id), null, null, true);
		$tag = $tag[0]["tag"];
	}
	else {
		$total = TagUtil::countAllObjectTags($brokers, true);
		$data = TagUtil::getAllObjectTags($brokers, $options, true);
	}
	
	$pks = "tag_id=#[\$idx][tag_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Tags List" . ($tag_id ? " for tag: '" . $tag . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_tag") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_tag") . $pks,
		"fields" => array(
			"tag_id", 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id",  
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_tags.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TagAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
