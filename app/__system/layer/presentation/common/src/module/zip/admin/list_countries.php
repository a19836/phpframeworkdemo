<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "country_id=#[\$idx][country_id]#";
	
	$list_settings = array(
		"title" => "Countries List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_country") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_country") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_states") . $pks),
		"fields" => array("country_id", "name", "created_date", "modified_date"),
		"data" => ZipUtil::getAllcountries($brokers, $options, true),
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_countries.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
