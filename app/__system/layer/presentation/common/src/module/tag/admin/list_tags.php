<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("tag/admin/TagAdminUtil", $common_project_name);
	
	$TagAdminUtil = new TagAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "tag_id=#[\$idx][tag_id]#";
	
	$list_settings = array(
		"title" => "Tags List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_tag") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_tag") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_object_tags") . $pks),
		"fields" => array("tag_id", "tag", "created_date", "modified_date"),
		"total" => TagUtil::countAllTags($brokers, true),
		"data" => TagUtil::getAllTags($brokers, $options, true),
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_tags.css" type="text/css" charset="utf-8" />';
	$menu_settings = $TagAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
